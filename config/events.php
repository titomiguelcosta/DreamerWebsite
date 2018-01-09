<?php

if (!DEBUG) {
    $app['dispatcher']->addListener(Symfony\Component\HttpKernel\KernelEvents::EXCEPTION, array($app['exception.handler'], 'handler'));
}

$app['dispatcher']->addListener(TitoMiguelCosta\Event\Contact::SUBMIT, array('TitoMiguelCosta\Listener\Contact', 'db'));
$app['dispatcher']->addListener(TitoMiguelCosta\Event\Contact::SUBMIT, array('TitoMiguelCosta\Listener\Contact', 'email'));

$app['dispatcher']->addListener(TitoMiguelCosta\Event\Exception::EXCEPTION_RAISED, array('TitoMiguelCosta\Listener\Exception', 'email'));