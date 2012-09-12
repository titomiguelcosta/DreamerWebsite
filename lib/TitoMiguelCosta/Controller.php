<?php

namespace TitoMiguelCosta;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Controller
{
    public function links(Request $request, Application $app)
    {
        return new Response($app['twig']->render('links.twig', array()));
    }
}