<?php

$app->get('/', 'TitoMiguelCosta\Controller\Site::indexAction')->bind('homepage');
$app->get('/profile', 'TitoMiguelCosta\Controller\Site::profileAction')->bind('profile');
$app->get('/projects', 'TitoMiguelCosta\Controller\Site::projectsAction')->bind('projects');
$app->get('/project/{slug}', 'TitoMiguelCosta\Controller\Site::projectAction')->bind('project');
$app->match('/contacts', 'TitoMiguelCosta\Controller\Site::contactAction')->bind('contact');
$app->get('/programming', 'TitoMiguelCosta\Controller\Site::programmingAction')->bind('programming');

$app->get('/blog', 'TitoMiguelCosta\Controller\Blog::listAction')->bind('blog_list');
$app->get('/blog/{slug}', 'TitoMiguelCosta\Controller\Blog::postAction')->bind('blog_post');

$app->get('/music/{page}', 'TitoMiguelCosta\Controller\Music::listAction')->value('page', '1')->bind('music');

$app->get('/images/{page}', 'TitoMiguelCosta\Controller\Image::listAction')->value('page', '1')->bind('images');

$app->get('/youtube/auth', 'TitoMiguelCosta\Controller\Main::youtubeAuthAction')->bind('youtube_auth');
$app->get('/youtube/token', 'TitoMiguelCosta\Controller\Main::youtubeTokenAction')->bind('youtube_token');