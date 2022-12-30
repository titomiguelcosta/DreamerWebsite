<?php

$app->register(new Silex\Provider\FormServiceProvider(), array());


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../templates',
    'twig.options' => array('cache' => __DIR__ . '/../data/cache'),
    'twig.form.templates' => array('form_div_layout.html.twig', 'form/custom_types.html.twig'),
));

if (defined('GOOGLE_RECAPTCHA_SITE_KEY')) {
    $app['twig']->addGlobal('gg_recaptcha_site_key', GOOGLE_RECAPTCHA_SITE_KEY);
} else {
    $app['twig']->addGlobal('gg_recaptcha_site_key', 'unknown');
}

$app->register(new Silex\Provider\ValidatorServiceProvider(), array());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../data/sql/db.sqlite',
    ),
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.options' => array(
        'host' => EMAIL_HOST,
        'port' => EMAIL_PORT,
        'username' => EMAIL_USERNAME,
        'password' => EMAIL_PASSWORD,
        'encryption' => EMAIL_ENCRYPTION,
        'auth_mode' => null
    )
));
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v2',
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
