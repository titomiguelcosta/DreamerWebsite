<?php

$app->get('/', 'TitoMiguelCosta\Controller\Site::indexAction')->bind('homepage');
$app->get('/profile', 'TitoMiguelCosta\Controller\Site::profileAction')->bind('profile');
$app->get('/studies', 'TitoMiguelCosta\Controller\Site::studiesAction')->bind('studies');
$app->get('/links', 'TitoMiguelCosta\Controller\Site::linksAction')->bind('links');
$app->match('/work', 'TitoMiguelCosta\Controller\Site::workAction')->bind('work');
$app->get('/projects', 'TitoMiguelCosta\Controller\Site::projectsAction')->bind('projects');
$app->get('/project/{slug}', 'TitoMiguelCosta\Controller\Site::projectAction')->bind('project');
$app->match('/contacts', 'TitoMiguelCosta\Controller\Site::contactAction')->bind('contact');
$app->get('/programming', 'TitoMiguelCosta\Controller\Site::programmingAction')->bind('programming');

$app->get('/blog', 'TitoMiguelCosta\Controller\Blog::listAction')->bind('blog_list');
$app->get('/blog/{slug}', 'TitoMiguelCosta\Controller\Blog::postAction')->bind('blog_post');