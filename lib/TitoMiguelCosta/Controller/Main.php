<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Silex\Application;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Main
{

    public function links(Application $app)
    {
        return new Response($app['twig']->render('links.twig', array()));
    }

    public function index(Application $app)
    {
        return $app['twig']->render('home.twig', array());
    }

    public function profile(Application $app)
    {
        return $app['twig']->render('profile.twig', array());
    }

    public function projects(Application $app)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));
        $projects = $crawler->filterXPath('//project');

        return $app['twig']->render(
            'projects.twig',
            array('projects' => $projects)
        );
    }

    public function project(Application $app, $slug)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));

        return $app['twig']->render('project.twig', array(
                    'project' => $crawler->filterXPath('//project[@slug="' . $slug . '"]')->children(),
                    'projects' => $crawler->filterXPath('//project[not(@slug="' . $slug . '")]')
                ));
    }

    public function contact(Application $app, Request $request)
    {
        $defaults = array(
            'name' => '',
            'email' => '',
        );

        $form = $app['form.factory']->create(new \TitoMiguelCosta\Form\Contact(), $defaults);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $app['dispatcher']->dispatch(\TitoMiguelCosta\Event\Contact::SUBMIT, new \TitoMiguelCosta\Event\Contact($app, $data));

                $app['session']->setFlash('name', $data['name']);

                return $app->redirect($app['url_generator']->generate('contact'));
            }
        }

        return $app['twig']->render('contact.twig', array(
                    'form' => $form->createView()
                ));
    }
    public function work(Application $app, Request $request)
    {
        $form = $app['form.factory']->create(new \TitoMiguelCosta\Form\Work());

        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $app['dispatcher']->dispatch(\TitoMiguelCosta\Event\Work::SUBMIT, new \TitoMiguelCosta\Event\Work($app, $data));

                $app['session']->setFlash('name', $data['name']);

                return $app->redirect($app['url_generator']->generate('work'));
            }
        }

        return $app['twig']->render('work.twig', array(
                    'form' => $form->createView()
                ));
    }
}
