<?
use yii\web\View;
//use yii\bootstrap\Dropdown;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $list string
 * @var $link string
 * @var $linkName string
 */
$this->registerJs('var list = ' . json_encode($list) . ';', View::POS_HEAD);
?>
<script type="text/x-template" id="list-template">
    <div class="row panel panel-default">
            <div class="col-md-3">
                <div class="crop-image thumbnail" v-if="Onelist.image" v-style="'background-image:url(<?=Yii::getAlias('@upload/');?>' + Onelist.image + ')'">
                    </div>
            </div>
            <div class="col-md-9">
                <h4>
                    {{ Onelist.name }}
                </h4>
                <p>
                    <a href="/chats/{{ Onelist.id }}" class="btn btn-danger" role="button"><?=Yii::t('app', $linkName);?></a>
                </p>
            </div>
    </div>
</script>
<div class="list" id="list_widget">
    <list v-ref="list" v-repeat="Onelist in list"></list>
</div>