<?php
use app\components\widgets\lists\ListsWidget;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $this yii\web\View
 * @var $country Array
 */
$this->title = 'Поиск';

$this->params['breadcrumbs'][] = [
    'label' => $country['name'],
    'url'   => [
        "/search/?country={$country['id']}"
    ],
];

if(isset($country['cities']) && !empty($country['cities'])) {
    $city = current($country['cities']);
    $this->params['breadcrumbs'][] = [
        'label' => $city['name'],
        'url'   => [
            "/search/?country={$country['id']}&city={$city['id']}"
        ],
    ];
    if(isset($city['districts'])) {
        $district = current($city['districts']);
        $this->params['breadcrumbs'][] = [
            'label' => $district['name'],
            'url'   => [
                "/search/?country={$country['id']}&city={$city['id']}&disctrict={$district['id']}"
            ],
        ];
    }
}

$perPage = Yii::$app->getRequest()->getQueryParam('per-page');
$page = Yii::$app->getRequest()->getQueryParam('page');
$pageSize = Yii::$app->params['pageSize'];
$stringCount = '<p id="string-count">';

if ($perPage && $page) {
    $firstItem = ($page-1)*$perPage + 1;
    $endItem = ($firstItem-1) + $perPage;
    $endItem = ($endItem > $districtCount)? $districtCount : $endItem;

    $stringCount .= Yii::t('app', 'показаны') . ' ' . $firstItem . ' - ' . $endItem;
} else {
    if ($districtCount < $pageSize) {
        $stringCount .= Yii::t('app', 'показаны') . ' 1' . ' - ' . $districtCount;
    } else {
        $stringCount .= Yii::t('app', 'показаны') . ' 1' . ' - ' . $pageSize;
    }
}

$stringCount .= '</p>';

?>
<? if(isset($background) && $background): ?>
    <div class="parent-city-img">
        <div class="inner">
            <div class="block" style="background: url('<?= Url::to('@upload/' . $background) ?>') no-repeat center;">
                <!--<img src="<? /*=Url::to('@upload/' . $background)*/ ?>" alt=""/>-->
            </div>
        </div>
    </div>
<? endif; ?>
<div class="row country-row">
    <div class="col-md-1"></div>
    <div class="col-md-10 country-row">
        <div class="row static-content">
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-body">
                        <h3 class="panel-title"><?= Yii::t('app', 'Your search') ?></h3>

                        <p class="list-group-item-text"><?= Yii::t('app', 'country') ?></p>
                        <h4 class="list-group-item-heading">
                            <?= $country['name'] ?>
                        </h4>

                        <p class="list-group-item-text"><?= Yii::t('app', 'city') ?></p>
                        <h4 class="list-group-item-heading">
                            <?= isset($city) ? $city['name'] : '<p class"text-muted">' . Yii::t('app', 'not choose') . '</p>'; ?>
                        </h4>

                        <p class="list-group-item-text"><?= Yii::t('app', 'district') ?></p>
                        <h4 class="list-group-item-heading">
                            <?= isset($district) && $district ? $district['name'] : Yii::t('app', 'not choose'); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h5>
                    <?= Yii::t('app', $country['name']) ?> <?= isset($city) ? '<span class="glyphicon glyphicon-chevron-right"></span> ' . $city['name'] : ''; ?> <?= isset($district) ? '<span class="glyphicon glyphicon-chevron-right"></span> ' . $district['name'] : ''; ?>
                    <?= (!isset($district) && $districtCount) ? ': ' . \Yii::t('app', '{count} districts are available', ['count' => $districtCount]) : ''; ?>
                </h5>
                <?= LinkPager::widget([
                    'pagination' => $pages,
                ]);?>
                <?=$stringCount;?>
                <?= ListsWidget::widget([
                    'list'     => $districtPaginator,
                    'link'     => '',
                    'linkName' => \Yii::t('app', 'Go'),
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-1 col-xs-1"></div>
</div>