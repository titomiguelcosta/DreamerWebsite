<?php

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
    'twig.options' => array('cache' => __DIR__ . '/../data/cache'),
));
$app->register(new Silex\Provider\FormServiceProvider(), array(
));
$app->register(new Silex\Provider\ValidatorServiceProvider(), array(
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/sql/db.sqlite',
    ),
));
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'switfmailer.options' => array(
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => 'titomiguelcosta@gmail.com',
        'password' => 'mvjwmvbdavxquyiw',
        'encryption' => 'ssl',
        'auth_mode' => null
    ))
);
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'css2', 'base_path' => '/css'),
        'images' => array('base_urls' => array('https://assets.titomiguelcosta.com')),
    ),
));
$app['exception.handler'] = function ($app) {
    return new TitoMiguelCosta\Listener\Exception($app);
};

$app['debug'] = defined("DEBUG") ? DEBUG : false;