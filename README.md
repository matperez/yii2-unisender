yii2 unisender
==============
yii2 unisender extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matperez/yii2- "~1.0"
```

or add

```
"matperez/yii2unisender": "~1.0"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :


```php

    'components' => [
      'unisender' => [
        'class' => \matperez\yii2unisender\UniSender::class,
        'apiConfig' => [
          'apiKey' => '...'
        ],
    ]
    
```

You can also use it with the dependency container:

```php

Yii::$container->setSingleton(\matperez\yii2unisender\UniSenderInterface::class, function() {
    return Yii::$app->unisender;
});

```


Tests
-----

Just run phpunit 