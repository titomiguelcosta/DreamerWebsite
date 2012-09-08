<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/settings.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.cache' => __DIR__.'/../cache',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider(), array(
));
$app->register(new Silex\Provider\FormServiceProvider(), array(
));
$app->register(new Silex\Provider\ValidatorServiceProvider(), array(
));

require_once __DIR__.'/../config/routing.php';

$app->run();