<? namespace app\components;

use app\models\Language;
use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages;

    public function bootstrap($app) {
        $this->supportedLanguages = Language::find()->select(['id', 'code', 'translit'])->indexBy('code')->all();
        $codes = array_keys($this->supportedLanguages);
        $app->cache->set('supportedLanguages', $codes);
        $preferredLanguage =
            isset($app->request->cookies['lang'])
            && in_array($app->request->cookies['lang']->value, $codes)
                ? (string)$app->request->cookies['lang']->value
                : $app->language;

        if($preferredLanguage != $app->cache->get('language')) {
            $app->cache->set('language', $this->supportedLanguages[$preferredLanguage]->toArray());
        }
        $app->language = $preferredLanguage;
    }
}