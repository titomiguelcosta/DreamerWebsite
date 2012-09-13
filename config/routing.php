<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;

/**
 * homepage
 */
$app->get('/', function () use ($app)
        {
            return $app['twig']->render('home.twig', array(
                    ));
        })->bind('homepage');

/**
 * profile
 */
$app->get('/profile', function () use ($app)
        {
            return $app['twig']->render('profile.twig', array(
                    ));
        })->bind('profile');
/**
 * links
 */
$app->get('/links', 'TitoMiguelCosta\Controller::links')->bind('links');

/** Projects * */
$app->get('/projects', function () use ($app)
        {
            $crawler = new Crawler();
            $crawler->addXmlContent(file_get_contents(PROJECT_ROOT.'/data/xml/projects.xml'));
            $projects = $crawler->filterXPath('//project');

            return $app['twig']->render('projects.twig', array(
                'projects' => $projects
                    ));
        })->bind('projects');

/** Project * */
$app->get('/project/{slug}', function ($slug) use ($app)
        {
            $crawler = new Crawler();
            $crawler->addXmlContent(file_get_contents(PROJECT_ROOT.'/data/xml/projects.xml'));

            return $app['twig']->render('project.twig', array(
                'project' => $crawler->filterXPath('//project[@slug="'.$slug.'"]')->children(),
                'projects' => $crawler->filterXPath('//project[not(@slug="'.$slug.'")]')
                    ));
        })->bind('project');
/**
 * contacts
 */
$app->match('/contacts', function (Request $request) use ($app)
        {
            $data = array(
                'name' => '',
                'email' => '',
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                    ->add('name', 'text')
                    ->add('email', 'text')
                    ->add('message', 'textarea')
                    ->getForm();

            if ('POST' == $request->getMethod())
            {
                $form->bind($request);

                if ($form->isValid())
                {
                    $data = $form->getData();
                    $app->register(new Silex\Provider\SwiftmailerServiceProvider());

                    $app['db']->insert('contact', array_merge($data, array('created_at' => time())));

                    $app['mailer']->send(new TitoMiguelCosta\Email\Contact($app, $data));
                    $app['session']->setFlash('name', $data['name']);

                    return $app->redirect('/contacts');
                }
            }
            return $app['twig']->render('contact.twig', array(
                        'form' => $form->createView()
                    ));
        })->bind('contact');
/**
 * request
 */
$app->match('/request', function (Request $request) use ($app)
        {
            $submit = false;
            $data = array(
                'name' => '',
                'email' => '',
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                    ->add('name', 'text')
                    ->add('email', 'text')
                    ->add('message', 'textarea')
                    ->getForm();

            if ('POST' == $request->getMethod())
            {
                $form->bind($request);

                if ($form->isValid())
                {
                    $app->register(new Silex\Provider\SwiftmailerServiceProvider());

                    $message = \Swift_Message::newInstance();
                    $app['mailer']->send($message);

                    $data = $form->getData();
                    $submit = true;

                    return $app->redirect('/contact');
                }
            }
            return $app['twig']->render('contact.twig', array(
                        'form' => $form->createView()
                    ));
        })->bind('request');