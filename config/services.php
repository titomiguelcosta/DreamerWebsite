<?php
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.options' => array('cache' => __DIR__ . '/../data/cache'),
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider(), array(
));
$app->register(new Silex\Provider\FormServiceProvider(), array(
));
$app->register(new Silex\Provider\ValidatorServiceProvider(), array(
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));
$app->register(new Silex\Provider\SessionServiceProvider(array(
)));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/sql/db.sqlite',
    ),
));
$app->register(new Silex\Provider\SwiftmailerServiceProvider(array(
    'switfmailer.options' => array(
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => 'titomiguelcosta@gmail.com',
        'password' => 'mvjwmvbdavxquyiw',
        'encryption' => 'ssl',
        'auth_mode' => null
    )
)));
$app['exception.handler'] = $app->share(function ($app) {
    return new TitoMiguelCosta\Listener\Exception($app);
});