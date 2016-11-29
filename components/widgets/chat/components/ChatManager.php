<?php namespace app\components\widgets\chat\components;

use app\components\widgets\chat\components\User as ChatUser;
use app\models\Message;
use app\models\MessageImage;
use app\models\security\User;
use Yii;

/**
 * Class ChatManager
 */
class ChatManager
{
    private $users = [];
    public $userClassName = null;

    public function __construct() {
        $this->userClassName = User::className();
    }

    /**
     * Check if user exists in list
     * return resource id if user in current chat - else null
     *
     * @access private
     * @param $id
     * @param $chatId
     * @return null|int
     */
    public function isUserExistsInChat($id, $chatId, $privateChat = false) {
        foreach($this->users as $rid => $user) {
            $chat = $user->getChat();
            if(!$chat) {
                continue;
            }
            if($user->id == $id && $chat->getUid() == $chatId) {
                return $rid;
            }
        }

        return null;
    }

    public function isUserExistsInChats($id) {
        foreach($this->users as $rid => $user) {
            if($user->id == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add new user to manager
     *
     * @access public
     * @param integer $rid
     * @param mixed $id
     * @param array $props
     * @return void
     */
    public function addUser($rid, $id, array $props = []) {
        $user = new ChatUser($id, $this->userClassName, $props);
        $user->setRid($rid);
        $this->users[$rid] = $user;
    }

    /**
     * Return if exists user chat room
     *
     * @access public
     * @param $rid
     */
    public function getUserChat($rid) {
        $user = $this->getUserByRid($rid);

        return $user ? $user->getChat() : null;
    }

    public function getUserPrivateChats($rid) {
        $user = $this->getUserByRid($rid);

        return $user->getPrivateChats();
    }

    /**
     * Find chat room by id, if not exists create new chat room
     * and assign to user by resource id
     *
     * @access public
     * @param $chatId
     * @param $rid
     */
    public function findChat($chatId, $rid, $privateChat = false) {
        $chat = null;
        $storedUser = $this->getUserByRid($rid);
        if($privateChat) {
            foreach($this->users as $user) {
                foreach($user->getPrivateChats() as $userChat) {
                    if($userChat->getUid() == $chatId) {
                        $chat = $userChat;
                        break;
                    }
                }
            }
            if(!$chat) $chat = new PrivateChatRoom;
            $storedUser->addPrivateChat($chat);
            $chat->addUser($storedUser);
        } else {
            foreach($this->users as $user) {
                $userChat = $user->getChat();
                if(!$userChat) {
                    continue;
                }
                if($userChat->getUid() == $chatId) {
                    $chat = $userChat;
                    break;
                }
            }
            if(!$chat) $chat = new DistrictChatRoom;
            $storedUser->setChat($chat);
        }

        $chat->setUid($chatId);

        return $chat;
    }

    /**
     * Get user by resource id
     *
     * @access public
     * @param $rid
     * @return ChatUser
     */
    public function getUserByRid($rid) {
        return !empty($this->users[$rid]) ? $this->users[$rid] : null;
    }

    /**
     * Find user by resource id and remove it from chat
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function removeUserFromChat($rid) {
        $user = $this->getUserByRid($rid);
        if(!$user) {
            return;
        }
        $chat = $user->getChat();
        $pChats = $user->getPrivateChats();
        if($chat) {
            $chat->removeUser($user);
        }
        if(!empty($pChats)) {
            foreach($pChats as $pChat) {
                $pChat->removeUser($user);
            }
        }
        unset($this->users[$rid]);
    }

    /**
     * Store chat message
     *
     * @access public
     * @param string $message
     */
    public function storeMessage(ChatUser $user, ChatRoomInterface $chat, $message, $image_id) {
        $chat_col = $chat instanceof PrivateChatRoom ? 'private_chat_id' : 'district_id';
        $params = [
            $chat_col  => $chat->getUid(),
            'user_id'  => $user->getId(),
            'username' => $user->username,
            'text'     => strip_tags($message)
        ];
        $message = new Message;
        $message->setAttributes($params);
        if($message->save()) {
            if($image_id) {
                if(($messageImage = MessageImage::findOne($image_id)) && $messageImage->created_by == $message->user_id) {
                    $message->link('image', $messageImage);
                    $message = $message->toArray();
                    if(is_null($message['image'])) {
                        $message['image'] = $messageImage->toArray();
                    }
                }
            }
            if(!is_array($message)) {
                $message = $message->toArray();
            }

            return $message;
        } else {
            dump($message->errors);
        }

        return false;
    }

    /**
     * Load user chat history
     *
     * @access public
     * @param mixed $chatId
     * @param integer $limit
     * @return array
     */
    public function getHistory($chatId, $offset = 0, $privateChat = false) {
        $limit = $privateChat ? PrivateChatRoom::MESSAGES_ON_PAGE : DistrictChatRoom::MESSAGES_ON_PAGE;
        $chatIdCol = $privateChat ? 'private_chat_id' : 'district_id';
        $messagesQuery = Message::find()
            ->where([$chatIdCol => $chatId])
            ->limit($limit)
            ->offset($offset);
        $totalCountQuery = clone $messagesQuery;

        $messages = $messagesQuery->with('image')->orderBy('id desc')->asArray()->all();
        $messages = array_map(function ($val) {
            if($val['image']) {
                $val['image']['bigImage'] = '/img/upload/messages/' . $val['image']['image'];
                $val['image']['image'] = '/img/upload/messages/mini_' . $val['image']['image'];
            }

            return $val;
        }, $messages);

        $history = [
            'messages'           => array_reverse($messages),
            'messagesTotalCount' => $totalCountQuery->count('id')
        ];

        return $history;
    }

    /**
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }
}