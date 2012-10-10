<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use Silex\Application;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Main
{

    public function linksAction(Application $app)
    {
        return $app['twig']->render('links.twig', array());
    }

    public function indexAction(Application $app)
    {
        return $app['twig']->render('home.twig', array());
    }

    public function profileAction(Application $app)
    {
        return $app['twig']->render('profile.twig', array());
    }
    public function studiesAction(Application $app)
    {
        return $app['twig']->render('studies.twig', array());
    }

    public function projectsAction(Application $app)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));
        $projects = $crawler->filterXPath('//project');
        $in_progress = $crawler->filterXPath('//project[status="In progress"]');

        return $app['twig']->render(
            'projects.twig',
            array('projects' => $projects, 'in_progress' => $in_progress)
        );
    }

    public function projectAction(Application $app, $slug)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));

        return $app['twig']->render('project.twig', array(
                    'project' => $crawler->filterXPath('//project[@slug="' . $slug . '"]')->children(),
                    'projects' => $crawler->filterXPath('//project[not(@slug="' . $slug . '")]')
                ));
    }

    public function contactAction(Application $app, Request $request)
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
    public function workAction(Application $app, Request $request)
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
