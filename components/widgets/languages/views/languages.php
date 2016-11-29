<?
use yii\web\View;
//use yii\bootstrap\Dropdown;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $languages string
 */
$this->registerJs('var languages = ' . json_encode($languages) . ';', View::POS_HEAD);
?>
<script type="text/x-template" id="languages-template">
    <li><a href="<?=\yii\helpers\Url::toRoute('language/set')?>?lang={{ language.code }}">{{ language.name }}</a></li>
</script>
<div class="dropdown" id="languages_widget">
    <button class="btn btn-default dropdown" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?//dump($languages);?>
        <?=Html::img('@upload/' . $languages[Yii::$app->language]['flag']);?>
        <!--<span class="caret"></span>-->
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <languages v-ref="languages" v-repeat="language in languages"></languages>
    </ul>
</div>