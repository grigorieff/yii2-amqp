Yii2 AMQP
=========


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist grigorieff/yii2-amqp "*"
```

or add

```json
"grigorieff/yii2-amqp": "*"
```

to the require section of your composer.json.

Configuration
------------

Add to your app config:

```php
    'components' => [

        .........

        'amqp' => [
            'class' => grigorieff\amqp\Amqp,
            'host' => 'localhost',
            'port' => '5672',
            'user' => 'guest',
            'password' => 'guest'
        ],

        .........

    ];
```

Usage
-----

```php

// get AMQP component
$amqp = Yii::$app->amqp;

// declare exchange
$amqp->exchangeDeclare('myExchange','fanout');

// declare queue
$amqp->queueDeclare('myQueue');

// binding queue
$amqp->bindQueueExchange('myQueue','myExchange');

// basic publish message
$amqp->basicPublish($message, $exchange, $routingKey);


......

```

License
-------

MIT

Requirements
------------
This Yii2 component require [PhpAmqpLib][1]


[1]:https://github.com/videlalvaro/php-amqplib

