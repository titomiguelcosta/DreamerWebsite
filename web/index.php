<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

$app = new Silex\Application();
$app['debug'] = false;
$app['root_dir'] = __DIR__;

require_once __DIR__.'/../config/services.php';
require_once __DIR__.'/../config/routing.php';
require_once __DIR__.'/../config/events.php';

use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpFoundation\Request;

$cache = new HttpCache($app, new Store(__DIR__.'/../data/cache'));
$request = Request::createFromGlobals();
$response = $cache->handle($request);
$response->send();
$cache->terminate($request, $response);