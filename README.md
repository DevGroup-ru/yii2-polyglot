Yii2 integration for Airbnb Polyglot.js
=======================================

[Polyglot.js](https://github.com/airbnb/polyglot.js) is a tiny and powerful JavaScript i18n library from Airbnb.

This is a yii2-extension that helps integrating polyglot.js into your yii2 application.

Usage
-----

First install extension through composer:

```
php composer.phar require --prefer-dist devgroup/yii2-polyglot
```

Create directory where you will place your translation js files. 
For example, if we our app is based on yii2-app-advanced - create directory `common/polyglot.js/`.

Now create your js translation files under this directory. 
Naming format is `common/polyglot.js/%LANGUAGE%.js`, where `%LANGUAGE%` is your `Yii::$app->language`, for example `common/polyglot.js/ru-RU.js`.

An example of js file contents(:en: version file `common/polyglot.js/en-US.js`:

```js
polyglot.extend({
  "nav": {
    "hello": "Hello",
    "hello_name": "Hello, %{name}",
    "sidebar": {
      "welcome": "Welcome"
    }
  }
});
```

And an example for :ru: version file `common/polyglot.js/ru-RU.js`:

```js
polyglot.extend({
  "nav": {
    "hello": "Привет",
    "hello_name": "Привет, %{name}",
    "sidebar": {
      "welcome": "Бобро пожаловать"
    }
  }
});
```

Now add a special yii2 application param `PolyglotTranslationPath` with your js translation path as value
into your config file(ie. `common/config/params.php`):

```php
<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    /**
     * This is a path where your js translation files are stored
     * You can use yii2 aliases here(@app, @common, etc.)
     */
    'PolyglotTranslationPath' => '@common/polyglot.js'
];
```

And the final thing is to register `PolyglotBundle` inside your layout view or add it as AssetBundle dependency.

For adding `PolyglotBundle` into your view just add the one line into 
`views/layouts/main.php` near your `AppAsset::register` call:

```php
DevGroup\Polyglot\PolyglotBundle::register($this);
```

For adding `PolyglotBundle` as dependency for your `AppAssetBundle` modify your `assets/AppAsset.php`:

```php
<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/dist/';

    public $css = [
        'styles/main.min.css',
    ];
    public $js = [
        'scripts/main.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        /// !!! This is a dependency to PolyglotBundle !!!
        'DevGroup\Polyglot\PolyglotBundle',
    ];
}

```

That's it. Now on every request you will get:

1. Polyglot.js will be included in HEAD section of your HTML
2. Global `polyglot` variable will be initialized with current locale(`Yii::$app->language`).
3. Translation file based on app language will be added dynamically right after `<body>` tag.

Now you can use `Polyglot.js` as it is described in [official documentation](http://airbnb.io/polyglot.js/#translation):

```js
polyglot.t("nav.sidebar.welcome");
=> "Бобро пожаловать"
```