<?
use app\components\widgets\chat\components\DistrictChatRoom;use yii\bootstrap\Modal;use yii\helpers\Url;use yii\web\View;

/**
 * @var $this yii\web\View
 * @var $chat Array
 * @var $smiles Array
 */
$chatId = $chat['id'];
$messagesOnPage = DistrictChatRoom::MESSAGES_ON_PAGE;
$smiles = json_encode($smiles);
$users = json_encode($users);
$user_id = Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->getId();
$locale = Yii::$app->language;

$javascript = <<<JS
    var chat_id = "$chatId";
    var messagesOnPage = "$messagesOnPage";
    var smiles = $smiles;
    var users = $users;
    var user_id = $user_id;
    var private_chat = false;
    var locale = "$locale";
JS;

$endJs = <<<JS
    if (typeof InstallTrigger !== 'undefined') {
       $('#conteiner-content').css('padding-bottom',  '53px');
    }
JS;

$this->registerJs(
    $javascript,
    View::POS_HEAD
);
$this->registerJs(
    $endJs,
    View::POS_END
);
$userAuthorized = !Yii::$app->user->isGuest;
?>
    <script type="text/x-template" id="messages-template">
        <div class="chat-item" v-repeat="message in messages">
            <div class="chat-item__container">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="chat-item__aside">
                            <div class="chat-item__avatar">
                                <a v-on="click: showDetailInfo(users[message.user_id])" href="javascript:void(0)">
                                    <div class="avatar crop-image thumbnail"
                                         v-style="'background-image:url(' + users[message.user_id].avatar + ')'"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="chat-item__content">
                            <div class="chat-item__details">
                                <div class="chat-item__from">
                                    <h4>{{ users[message.user_id].username }}</h4>
                                    <? if($userAuthorized): ?>
                                        <a v-if="message.user_id != user_id" href="javascript:void(0);"
                                           v-on="click: sendInviteToPrivateChat(message.user_id, this.chat_id)">
                                        <span class="label label-default">
                                            <?= Yii::t('app', 'Invite to private chat') ?>
                                        </span>
                                        </a>
                                    <? endif; ?>
                                </div>
                                <div class="chat-item__time">
                                    <span>
                                        {{ message.created_at | moment 'LL LT' }}
                                    </span>
                                </div>
                            </div>
                            <div class="chat-item__text js-chat-item-text">{{{ message.text | replaceSmiles }}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <? if($userAuthorized && (strripos(Url::to(), 'chats') !== false) || strripos(Url::to(), 'private') !== false): ?>
        <a href="/personal/update" role="button" class="btn btn-danger" id="profile-settings-btn"/><?=Yii::t('app', 'Profile settings')?></a>
    <? endif;?>
    <div class="row" id="chat_widget">
        <div class="row panel panel-defaul" id="message-box" v-on="scroll: handleScroll">
            <messages messages="{{messages}}" users="{{users}}" user_id="{{user_id}}"></messages>
        </div>
            <div class="row bl-form">
                <div class="col-md-12">
                    <form class="message-form" v-on="submit: sendMessage">
                        <div class="input-group">
                            <a href="javascript:void(0);" id="smiles_btn"></a>
                            <textarea class="form-control" v-emojiarea v-model="form.text"></textarea>
                            <span class="input-group-btn">
                                <button v-attr="disabled: form.text.length == 0" class="btn btn-danger" type="submit" id="message-submit">
                                    <?= Yii::t('app', 'Send') ?>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        <? if($userAuthorized): ?>
            <invite_form v-ref="invite_form"></invite_form>
            <invite_accepted_modal v-ref="invite_accepted_modal"></invite_accepted_modal>
            <invite_detail_info_modal v-ref="invite_detail_info_modal"></invite_detail_info_modal>
        <? endif; ?>
    </div>
    <script id="inviteDetailInfoModalComponentTemplate" type="text/html">
        <? Modal::begin([
            'header' => '<h3>{{ user.username }}</h3>',
            'id'     => 'inviteDetailInfoModalComponentTemplate'
        ]); ?>
        <div class="row">
            <div class="col-md-4">
                <div class="avatar crop-image thumbnail" v-style="'background-image:url(' + user.avatar + ')'"></div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">Страна:</div>
                    <div class="col-md-8"><b>{{ user.country }}</b></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Город:</div>
                    <div class="col-md-8"><b>{{ user.city }}</b></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Район:</div>
                    <div class="col-md-8"><b>{{ user.district }}</b></div>
                </div>
            </div>
        </div>
        <? Modal::end(); ?>
    </script>

<? if($userAuthorized): ?>
    <? Modal::begin([
        'header' => '<h3>Приглашение отклонено</h3>',
        'id'     => 'inviteDeclinedModal'
    ]); ?>
    <div class="modal_content">
        Пользователь отклонил приглашение в приватный чат
    </div>
    <? Modal::end(); ?>

    <script id="inviteFormComponentTemplate" type="text/html">
        <? Modal::begin([
            'header' => '<h3>' .
                Yii::t('app', 'Invite in private chat from user: ') .
                '<span id="inviteFormComponentTemplate-name">{{ user.username }}</span></h3>',
            'id'     => 'inviteFormModal'
        ]); ?>
        <div class="invite_form_wrap">
            <div class="invite_form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="avatar crop-image thumbnail"
                             v-style="'background-image:url(' + user.avatar + ')'"></div>
                    </div>
                    <div class="col-md-8" id="inviteFormComponentTemplate-buttons">
                        <a v-on="click: acceptInvite(user.id)" class="btn btn-green">Принять</a>
                        <button v-on="click: declineInvite(user.id)" class="btn btn-danger">Отклонить</button>
                    </div>
                </div>
            </div>
        </div>
        <? Modal::end(); ?>
    </script>

    <script id="inviteAcceptedModalComponentTemplate" type="text/html">
        <? Modal::begin([
            'header' => '<h3>' . Yii::t('app', 'Private chat is create ') .
                '</h3><a id="inviteAcceptedModal-link" target="_blank" v-on="hide" href="/privatechats/{{ chat.id }}" class="btn btn-danger">' .
                Yii::t('app', 'Go to private') . '</a>',
            'id'     => 'inviteAcceptedModal'
        ]); ?>
        <? Modal::end(); ?>
    </script>

    <? Modal::begin([
        'header' => '<h3>' . Yii::t('app', 'Invite is been send') . '</h3>',
        'id'     => 'inviteSendedModal'
    ]); ?>
    <div class="modal_content">
        <? Yii::t('app', 'Invite to private is been send')?>
    </div>
    <? Modal::end(); ?>

    <? Modal::begin([
        'header' => '<h3>Пользователь отсутствует в чате</h3>',
        'id'     => 'inviteFailedModal'
    ]); ?>
    <? Modal::end(); ?>

<? else: ?>
    <?
    $this->registerCss("#footer-line{height:39px; padding-top: 3px;}");
    $js = <<<JS
    $(function(){
        $(".sign-buttons").attr('role', 'button').addClass('btn btn-danger');
        $("#login_button").on('click', function(event) {
            event.preventDefault();
            $('#signup-wrap').hide();
            $('#signin-wrap').show();
        });
        $("#reg-button").on('click', function(event) {
            event.preventDefault();
            $('#signin-wrap').hide();
            $('#signup-wrap').show();
        });
        $('.close-btn').on('click', function(){
            $(this).closest('.login_wrap').hide()
        });
    })
JS;
    $this->registerJs(
        $js,
        View::POS_END
    );
    if(!$userAuthorized){
        $js = <<<JS
        $(function(){
            $('.emoji-wysiwyg-editor').attr('contenteditable', 'false');
            $('#smiles_btn').addClass('disabled-link');
        });
JS;
        $this->registerJs(
            $js,
            View::POS_END
        );
    }
    ?>
<? endif; ?>