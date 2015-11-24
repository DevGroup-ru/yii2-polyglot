<?php

namespace DevGroup\Polyglot;

use Yii;
use yii\helpers\Json;
use yii\web\AssetBundle;

/**
 * Class PolyglotBundle is the main AssetBundle for including Polyglot.js into your application.
 *
 * @package DevGroup\Polyglot
 */
class PolyglotBundle extends AssetBundle
{
    public $sourcePath = '@bower/polyglot/build';
    public $js = [
        'polyglot.min.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
        // This will add current needed translation to polyglot
        'DevGroup\Polyglot\CurrentTranslation',
    ];

    /**
     * This function extends default AssetBundle behavior adding Polyglot initialization call
     * right after including asset files
     *
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $localeEncoded = Json::encode(Yii::$app->language);

        $js = <<<js
var polyglot = new Polyglot({locale: $localeEncoded});
js;

        Yii::$app->controller->view->registerJs($js, \yii\web\View::POS_HEAD);
    }
}
