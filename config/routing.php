<?php

$app->get('/', 'TitoMiguelCosta\Controller\Main::index')->bind('homepage');
$app->get('/profile', 'TitoMiguelCosta\Controller\Main::profile')->bind('profile');
$app->get('/links', 'TitoMiguelCosta\Controller\Main::links')->bind('links');
$app->match('/work', 'TitoMiguelCosta\Controller\Main::work')->bind('work');
$app->get('/projects', 'TitoMiguelCosta\Controller\Main::projects')->bind('projects');
$app->get('/project/{slug}', 'TitoMiguelCosta\Controller\Main::project')->bind('project');
$app->match('/contacts', 'TitoMiguelCosta\Controller\Main::contact')->bind('contact');