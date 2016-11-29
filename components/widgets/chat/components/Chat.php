<?php namespace app\components\widgets\chat\components;

use app\models\PrivateChat;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Yii;
use yii\db\Query;
use yii\helpers\Json;

class Chat implements MessageComponentInterface
{
    /** @var ConnectionInterface[] */
    private $clients = [];
    /** @var ChatManager */
    private $cm = null;
    /**
     * @var array list of available requests
     */
    private $requests = [
        'auth',
        'message',
        'history',
        'privateChat',
        'acceptPrivateChat',
        'declinePrivateChat',
    ];

    /**
     * @param ChatManager $cm
     */
    public function __construct(ChatManager $cm) {
        $this->cm = $cm;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        $rid = $this->getResourceId($conn);
        $this->clients[$rid] = $conn;
        Yii::info('Connection is established: ' . $rid, 'chat');
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $data = Json::decode($msg, true);
        $sessionId = $from->WebSocket->request->getCookies()['_tworest_session'] ?: $data['sessid'];
        Yii::$app->language = Yii::$app->cache->get('language')['code'];
        $query = new Query;
        $query->select('user_id')
            ->from('session')
            ->where('[[expire]]>:expire AND [[id]]=:id', [':expire' => time(), ':id' => $sessionId]);
        $row = $query->one();
        $userId = $row['user_id'];
        if(in_array($data['type'], ['auth', 'message', 'history']) && $data['private_chat']) {
            $privateChatExists = PrivateChat::find()->andFilterWhere(
                [
                    'or',
                    ['owner_id' => $userId],
                    ['guest_id' => $userId]
                ]
            )->exists();
            if(!$privateChatExists) {
                return false;
            }
        }
        $rid = array_search($from, $this->clients);
        if(in_array($data['type'], $this->requests)) {
            call_user_func_array([$this, $data['type'] . 'Request'], [$rid, $userId, $data]);
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        $rid = array_search($conn, $this->clients);
        if($this->cm->getUserByRid($rid)) {
            $this->closeRequest($rid);
        }
        unset($this->clients[$rid]);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->send(Json::encode([
            'type' => 'error',
            'data' => [
                'message' => Yii::t('app', $e->getMessage() . $e->getTraceAsString())
            ]
        ]));
        $conn->close();
    }

    /**
     * Get connection resource id
     *
     * @access private
     * @param ConnectionInterface $conn
     * @return string
     */
    private function getResourceId(ConnectionInterface $conn) {
        return $conn->resourceId;
    }

    /**
     * Process auth request. Find user chat(if not exists - create it)
     * and send message to all other clients
     *
     * @access private
     * @param $rid
     * @param $data
     * @return void
     */
    private function authRequest($rid, $userId, array $data) {
        $chatId = $data['chat_id'];
        $privateChat = array_key_exists('private_chat', $data) && $data['private_chat'] == true;
//        if($oldRid = $this->cm->isUserExistsInChat($userId, $chatId, $privateChat)) {
//            $this->closeRequest($oldRid);
//        }

        $this->cm->addUser($rid, $userId);
        $chat = $this->cm->findChat($chatId, $rid, $privateChat);
        $users = $chat->getUsers();

        $history = $this->cm->getHistory($chat->getUid(), 0, $privateChat);
        $response = [
            'users'              => $users,
            'messages'           => $history['messages'],
            'messagesTotalCount' => $history['messagesTotalCount']
        ];
        $this->clients[$rid]->send(Json::encode(['type' => 'auth', 'data' => $response + ['self' => true]]));
        foreach($users as $user) {
            if($user->id != $userId) {
                $this->clients[$user->getRid()]->send(Json::encode([
                    'type' => 'newuser',
                    'data' => ['user' => $this->cm->getUserByRid($rid)]
                ]));
            }
        }
    }

    /**
     * Process message request. Find user chat room and send message to other users
     * in this chat room
     *
     * @access private
     * @param $rid
     * @param array $data
     * @return void
     */
    private function messageRequest($rid, $userId, array $data) {
        $privateChat = array_key_exists('private_chat', $data) && $data['private_chat'] == true;
        if($privateChat) {
            $chats = $this->cm->getUserPrivateChats($rid);

            foreach($chats as $pChat) {
                if($pChat->getUid() == $data['chat_id']) {
                    $chat = $pChat;
                }
            }
        } else {
            $chat = $this->cm->getUserChat($rid);
        }
        if(!$chat) {
            return;
        }
        $user = $this->cm->getUserByRid($rid);
        $data['user'] = $user;
        $data['message'] = $this->cm->storeMessage($user, $chat, $data['message'], $data['image_id']);
        $chatUsers = $chat->getUsers();
        if(count($chatUsers) < 2) {
            $data['alone'] = true;
        }
        foreach($chatUsers as $user) {
            $conn = $this->clients[$user->getRid()];
            $conn->send(Json::encode(['type' => 'message', 'data' => $data]));
        }
    }

    /**
     * Process close request. Find user chat, remove user from chat and send message
     * to other users in this chat
     *
     * @access public
     * @param $rid
     */
    private function closeRequest($rid) {
        $requestUser = $this->cm->getUserByRid($rid);
        $this->cm->removeUserFromChat($rid);
    }

    private function historyRequest($rid, $userId, array $data) {
        $privateChat = array_key_exists('private_chat', $data) && $data['private_chat'] == true;
        if($privateChat) {
            $chats = $this->cm->getUserPrivateChats($rid);
            foreach($chats as $pChat) {
                if($pChat->getUid() == $data['chat_id']) {
                    $chat = $pChat;
                }
            }
        } else {
            $chat = $this->cm->getUserChat($rid);
        }

        //if($this->cm->isUserExistsInChat($userId, $chat->getUid()));
        $history = $this->cm->getHistory($chat->getUid(), $data['offset'], $privateChat);
        $response = [
            'messages' => $history['messages']
        ];
        $conn = $this->clients[$rid];
        $conn->send(Json::encode(['type' => 'history', 'data' => $response]));
    }

    private function privateChatRequest($rid, $userId, array $data) {
        if(!array_key_exists('user_id', $data) || !array_key_exists('chat_id', $data)) {
            $conn = $this->clients[$rid];
            $conn->send(Json::encode(['type' => 'error', 'data' => 'error']));
        }

        #send message to sender
        $response = [
            'result' => (bool)$this->cm->isUserExistsInChats($data['user_id'])
        ];
        $this->clients[$rid]->send(Json::encode(['type' => 'inviteToPrivateChatSended', 'data' => $response]));

        #send message to receiver
        $response = [
            'user' => $this->cm->getUserByRid($rid)
        ];
        foreach($this->cm->getUsers() as $clientRid => $user) {
            if($user->id == $data['user_id']) {
                $this->clients[$clientRid]->send(Json::encode(['type' => 'privateChat', 'data' => $response]));
            }
        }

    }

    private function acceptPrivateChatRequest($rid, $userId, array $data) {
        if(!array_key_exists('user_id', $data)) {
            $this->clients[$rid]->send(Json::encode(['type' => 'error', 'data' => 'error']));
        }

        $model = PrivateChat::find()->where(
            [
                'or',
                ['owner_id' => $data['user_id'], 'guest_id' => $userId],
                ['owner_id' => $userId, 'guest_id' => $data['user_id']]
            ]
        )->one() ?: new PrivateChat;
        $model->setAttribute('owner_id', $data['user_id']);
        $model->setAttribute('guest_id', $userId);
        if($model->save()) {
            $response = [
                'user'     => $this->cm->getUserByRid($rid),
                'owner_id' => $data['user_id'],
                'chat'     => $model->toArray()
            ];
            foreach($this->cm->getUsers() as $clientRid => $user) {
                if($user->id == $data['user_id']) {
                    $this->clients[$clientRid]->send(Json::encode([
                        'type' => 'acceptPrivateChat',
                        'data' => $response
                    ]));
                }
            }
            $this->clients[$rid]->send(Json::encode([
                'type' => 'acceptPrivateChat',
                'data' => $response
            ]));
        }
    }

    private function declinePrivateChatRequest($rid, $userId, array $data) {
        if(!array_key_exists('user_id', $data)) {
            $conn = $this->clients[$rid];
            $conn->send(Json::encode(['type' => 'error', 'data' => 'error']));
        }

        $response = [
            'user' => $this->cm->getUserByRid($rid)
        ];

        foreach($this->cm->getUsers() as $clientRid => $user) {
            if($user->id == $data['user_id']) {
                $this->clients[$clientRid]->send(Json::encode(['type' => 'declinePrivateChat', 'data' => $response]));
            }
        }
    }

}