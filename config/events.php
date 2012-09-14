<?php
$app['dispatcher']->addListener(TitoMiguelCosta\Event\Contact::SUBMIT, array('TitoMiguelCosta\Listener\Contact', 'db'));
$app['dispatcher']->addListener(TitoMiguelCosta\Event\Contact::SUBMIT, array('TitoMiguelCosta\Listener\Contact', 'email'));

$app['dispatcher']->addListener(TitoMiguelCosta\Event\Work::SUBMIT, array('TitoMiguelCosta\Listener\Work', 'email'));