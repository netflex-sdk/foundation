<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Netflex\Foundation\Setting;
use Netflex\Foundation\StaticContent;
use Netflex\Foundation\Template;

use Netflex\API\Providers\APIServiceProvider;

// ####### Bootstrapping #######
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\CacheManager;

Dotenv::create(__DIR__)->load();

$container = new Container;

$container['config'] = [
  'api.publicKey' => getenv('NETFLEX_PUBLIC_KEY'),
  'api.privateKey' => getenv('NETFLEX_PRIVATE_KEY'),
  'cache.default' => 'file',
  'cache.stores.file' => [
    'driver' => 'file',
    'path' => __DIR__ . '/cache'
  ]
];

$container['files'] = new Filesystem;
$cacheManager = new CacheManager($container);
$container['cache'] = $cacheManager->store();

(new APIServiceProvider($container))->register();

Facade::setFacadeApplication($container);

// ####### Testcode #######
