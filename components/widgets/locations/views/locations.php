<?
use yii\web\View;

/**
 * @var $this yii\web\View
 * @var $locations Array
 */
//dump($locations);
$this->registerJs('var locations = ' . json_encode($locations) . ';', View::POS_HEAD);
?>
<div class="row" id="locations_widget">
    <form action="/search/">
        <div class="select country_select form-group">
            <label for="selected_country"><?= Yii::t('app', 'Country') ?></label>
            <select name="country" v-select="selected_country" class="form-control" id="selected_country"
                    v-model="selected_country">
                <option value=""><?= Yii::t('app', 'Un select') ?></option>
                <option v-repeat="country in locations" value="{{country.id}}">
                    {{ country.name }}
                </option>
            </select>
        </div>
        <div class="select city_select form-group">
            <label for="selected_city">
                <?= Yii::t('app', 'City') ?>
            </label>
            <select v-attr="disabled: !selected_country" name="city" v-select="selected_city" class="form-control"
                    id="selected_city"
                    v-model="selected_city">
                <option value=""><?= Yii::t('app', 'Un select') ?></option>
                <option v-repeat="city in locations[selected_country].cities" value="{{city.id}}">
                    {{ city.name }}
                </option>
            </select>
        </div>
        <div class="select district_select form-group">
            <label for="selected_district">
                <?= Yii::t('app', 'District') ?>
            </label>
            <select v-attr="disabled: !selected_city || !selected_country" v-select="selected_district"
                    class="form-control" id="selected_district"
                    v-model="selected_district" name="district">
                <option value=""><?= Yii::t('app', 'Un select') ?></option>
                <option v-repeat="district in locations[selected_country].cities[selected_city].districts"
                        value="{{district.id}}">
                    {{ district.name }}
                </option>
            </select>
        </div>
        <div class="form-actions text-center">
            <button v-attr="disabled: !selected_country" class="btn btn-danger btn-lg"><?= Yii::t('app', 'Search') ?></button>
        </div>
    </form>
</div>