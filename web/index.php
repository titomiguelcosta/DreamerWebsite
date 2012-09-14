<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/settings.php';

$app = new Silex\Application();
$app['debug'] = true;

require_once __DIR__.'/../config/services.php';
require_once __DIR__.'/../config/routing.php';
require_once __DIR__.'/../config/events.php';

$app->run();