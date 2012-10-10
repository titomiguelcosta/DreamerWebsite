<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/settings.php';

use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = false;

require_once __DIR__.'/../config/services.php';
require_once __DIR__.'/../config/routing.php';
require_once __DIR__.'/../config/events.php';

/* with cache */
$cache = new HttpCache($app, new Store(__DIR__.'/../data/cache'));
$request = Request::createFromGlobals();
$response = $cache->handle($request);
// expiration model
$date = new DateTime();
$date->modify('+600 seconds');
$response->setExpires($date);
$response->setPublic();

// validation model
//$response->send();
echo $response;

$cache->terminate($request, $response);

/* without cache */
//$app->run();