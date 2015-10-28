yii2 unisender
==============
yii2 unisender extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matperez/yii2unisender "~1.0"
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

For now it's supports the following operations:

Get a user fields array:

```php

    $fields = $unisender->getFields()->getResult;

```

Get an available subscriptions list:

```php

    $list = $unisender->getLists()->getResult();
  
```

Create a new subscription:

```php

    $sub = new Subscriber($name, $email, $phone);
    $sub->addTag('something');
    $response = $unisender->subscribe($sub, [12315, 14333]);
    if ($response->isSuccess()) {
      $personId = $response->getResult()['person_id'];
      // .... do something with the person id
    }
  
```

Get the "native" API instance:

```php

    $api = $unisender->getApi();
    $api->doSomethingViaMagicCall();
  
```

Tests
-----

Just run phpunit 
