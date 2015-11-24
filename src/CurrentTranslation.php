<?php

namespace DevGroup\Polyglot;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * Class CurrentTranslation is a special AssetBundle, that dynamically adds needed translation based on requested lang.
 *
 * @package DevGroup\Polyglot
 */
class CurrentTranslation extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (!isset(Yii::$app->params['PolyglotTranslationPath'])) {
            throw new InvalidConfigException('You should set yii2 application param PolyglotTranslationPath');
        }
        $this->sourcePath = rtrim(Yii::$app->params['PolyglotTranslationPath'], '/\\');

        parent::init();
    }

    /**
     * This function extends default AssetBundle behavior - this will dynamically add needed file with js translation
     * based on current requested language.
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        $language = Yii::$app->language;
        if (file_exists($this->sourcePath . "/$language.js") !== false) {
            $this->js = [
                "/$language.js"
            ];
        }

        parent::registerAssetFiles($view);
    }
}
