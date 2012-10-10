<?php

$app->get('/', 'TitoMiguelCosta\Controller\Main::indexAction')->bind('homepage');
$app->get('/profile', 'TitoMiguelCosta\Controller\Main::profileAction')->bind('profile');
$app->get('/studies', 'TitoMiguelCosta\Controller\Main::studiesAction')->bind('studies');
$app->get('/links', 'TitoMiguelCosta\Controller\Main::linksAction')->bind('links');
$app->match('/work', 'TitoMiguelCosta\Controller\Main::workAction')->bind('work');
$app->get('/projects', 'TitoMiguelCosta\Controller\Main::projectsAction')->bind('projects');
$app->get('/project/{slug}', 'TitoMiguelCosta\Controller\Main::projectAction')->bind('project');
$app->match('/contacts', 'TitoMiguelCosta\Controller\Main::contactAction')->bind('contact');