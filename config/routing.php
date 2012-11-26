<?php

$app->get('/', 'TitoMiguelCosta\Controller\Site::indexAction')->bind('homepage');
$app->get('/profile', 'TitoMiguelCosta\Controller\Site::profileAction')->bind('profile');
$app->get('/projects', 'TitoMiguelCosta\Controller\Site::projectsAction')->bind('projects');
$app->get('/project/{slug}', 'TitoMiguelCosta\Controller\Site::projectAction')->bind('project');
$app->match('/contacts', 'TitoMiguelCosta\Controller\Site::contactAction')->bind('contact');
$app->get('/code', 'TitoMiguelCosta\Controller\Site::codeAction')->bind('code');

$app->get('/blog/{page}', 'TitoMiguelCosta\Controller\Blog::listAction')->assert('page', '\d+')->value('page', '1')->bind('blog_list');
$app->get('/blog/{slug}', 'TitoMiguelCosta\Controller\Blog::postAction')->bind('blog_post');
$app->get('/blog/{category}/category', 'TitoMiguelCosta\Controller\Blog::categoryAction')->bind('blog_category');

$app->get('/music/{page}', 'TitoMiguelCosta\Controller\Music::listAction')->assert('page', '\d+')->value('page', '1')->bind('music');

$app->get('/photos/{page}', 'TitoMiguelCosta\Controller\Photo::listAction')->assert('page', '\d+')->value('page', '1')->bind('photos');

/** auxiliar routes */
$app->get('/youtube/auth', 'TitoMiguelCosta\Controller\Main::youtubeAuthAction')->bind('youtube_auth');
$app->get('/youtube/token', 'TitoMiguelCosta\Controller\Main::youtubeTokenAction')->bind('youtube_token');