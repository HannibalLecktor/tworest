<?
use app\components\widgets\chat\components\DistrictChatRoom;
use dosamigos\fileupload\FileUpload;
use yii\bootstrap\Modal;
use yii\web\View;

/**
 * @var $this yii\web\View
 * @var $chat Array
 * @var $smiles Array
 */
$chatId = $chat['id'];
$messagesOnPage = DistrictChatRoom::MESSAGES_ON_PAGE;
$smiles = json_encode($smiles);
$users = json_encode($users);
$user_id = Yii::$app->user->identity->getId();
$locale = Yii::$app->language;

$javascript = <<<JS
    var chat_id = "$chatId";
    var messagesOnPage = "$messagesOnPage";
    var smiles = $smiles;
    var users = $users;
    var user_id = $user_id;
    var private_chat = true;
    var locale = "$locale";
JS;

$this->registerJs(
    $javascript,
    View::POS_HEAD
);
$this->registerJs(
    "$(function() {
        $('#image-upload-btn').click(function(event){
            event.preventDefault();
            $('#messageimage-image').click();
        });
        $('#message-submit').click(function(event){
            img = $('.uploaded_thumbnail');
            if (img) {
                $(img).hide();
            }
        });
       if (typeof InstallTrigger !== 'undefined') {
           $('#conteiner-content').css('padding-bottom',  '53px');
        }
    })",
    View::POS_END
);
?>
<a href="/personal/update" role="button" class="btn btn-danger"
   id="profile-settings-btn"><?= Yii::t('app', 'Profile settings') ?></a>
<a href="javascript:void(0)" role="button" class="btn btn-danger"
   id="image-upload-btn"><?= Yii::t('app', 'Add image') ?></a>
<div class="row" id="chat_widget">
    <div class="row panel panel-defaul" id="message-box" v-on="scroll: handleScroll">
        <messages messages="{{ messages }}" users="{{ users }}" user_id="{{ user_id }}"></messages>
    </div>
    <div class="row bl-form">
        <div class="col-md-12">
            <form class="message-form" v-on="submit: sendMessage">
                <div class="input-group">
                    <?= FileUpload::widget([
                        'model'         => $model,
                        'attribute'     => 'image',
                        'url'           => ['message-images/upload', 'id' => $model->id],
                        'options'       => ['accept' => 'image/*'],
                        'clientOptions' => [
                            'maxFileSize' => 2000000
                        ],
                        'clientEvents'  => [
                            'fileuploaddone' => 'function(e, data) {
                                    var result = JSON.parse(data.result);
                                    if(!vm.form.text) vm.form.$set("text", "&nbsp;");
                                    vm.form.$set("image_id", result.id);
                                    vm.sendMessage(vm.$event);
                                    //vm.form.$set("thumbnailUrl", result.thumbnailUrl);

                                }',
                            'fileuploadfail' => 'function(e, data) {
                                    $.fancybox("<h3>Ошибка при загрузке файла.</h3>Размер файла не должен превышать 2мб.<br> Допустимые форматы: jpg, png, gif")
                                }',
                        ],
                    ]); ?>
                    <a href="javascript:void(0);" id="smiles_btn"></a>
                    <textarea class="form-control" v-emojiarea v-model="form.text"></textarea>
                    <span class="input-group-btn">
                        <button v-attr="disabled: form.text.length == 0" class="btn btn-danger" type="submit"
                                id="message-submit">
                            <?= Yii::t('app', 'Send') ?>
                        </button>
                    </span>
                    <img class="uploaded_thumbnail" v-show="form.thumbnailUrl" src="" v-attr="src: form.thumbnailUrl">
                </div>
            </form>
        </div>
    </div>
    <invite_detail_info_modal v-ref="invite_detail_info_modal"></invite_detail_info_modal>
</div>

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
                            </div>
                            <div class="chat-item__time">
                                        <span>
                                            {{ message.created_at | moment 'LL LT' }}
                                        </span>
                            </div>
                        </div>
                        <div class="chat-item__text js-chat-item-text">{{{ message.text | replaceSmiles }}}</div>
                        <div class="chat-item__img" v-if="message.image">
                            <a href="{{ message.image.bigImage }}" class="fancybox">
                                <img src="" class="message_image" v-attr="src: message.image.image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
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