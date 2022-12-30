<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$app = new Silex\Application();
$app['debug'] = defined("DEBUG") ? DEBUG : false;
$app['root_dir'] = __DIR__;

require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/../config/routing.php';
require_once __DIR__ . '/../config/events.php';

use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpFoundation\Request;

$app->extend('twig.runtimes', function ($runtimes, $app) {
    return array_merge($runtimes, [
        FormRenderer::class => 'twig.form.renderer',
    ]);

    if (defined('GOOGLE_RECAPTCHA_SITE_KEY')) {
        $app['twig']->addGlobal('gg_recaptcha_site_key', GOOGLE_RECAPTCHA_SITE_KEY);
    }
});

$cache = new HttpCache($app, new Store(__DIR__ . '/../data/cache'));
$request = Request::createFromGlobals();
$response = $cache->handle($request);
$response->send();
$cache->terminate($request, $response);
