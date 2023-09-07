Installation
============

```
$ composer require janmuran/arukereso-trusted-shop
```

Usage
=====

The simplest usage :

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';


try {
    $client = new \Janmuran\TrustedShop('api - key');
    $client->setEmail('somebody@example.com');
    $client->addProduct('Product name 1', 'P123456');

    echo $client->createTrustedCode();
} catch (Exception $exception) {
  die($exception->getMessage());
}
```