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
class Site
{
    public function indexAction(Application $app)
    {
        /* @var $response \Symfony\Component\HttpFoundation\Response */
        $response = new Response($app['twig']->render('site/home.twig', array()));
        $response->setPublic();
        $response->setExpires(new \DateTime('+60 seconds'));

        return $response;
    }

    public function profileAction(Application $app)
    {
        return $app['twig']->render('site/profile.twig', array());
    }

    public function programmingAction(Application $app)
    {
        return $app['twig']->render('site/programming.twig', array());
    }

    public function projectsAction(Application $app)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));
        $projects = $crawler->filterXPath('//project');
        $in_progress = $crawler->filterXPath('//project[status="In progress"]');

        return $app['twig']->render('site/projects.twig', array(
                    'projects' => $projects,
                    'in_progress' => $in_progress
                ));
    }

    public function projectAction(Application $app, $slug)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/projects.xml'));

        return $app['twig']->render('site/project.twig', array(
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

        if ('POST' == $request->getMethod())
        {
            $form->bind($request);

            if ($form->isValid())
            {
                $data = $form->getData();
                $app['dispatcher']->dispatch(\TitoMiguelCosta\Event\Contact::SUBMIT, new \TitoMiguelCosta\Event\Contact($app, $data));

                $app['session']->setFlash('name', $data['name']);

                return $app->redirect($app['url_generator']->generate('contact'));
            }
        }

        return $app['twig']->render('site/contact.twig', array(
                    'form' => $form->createView()
                ));
    }

}
