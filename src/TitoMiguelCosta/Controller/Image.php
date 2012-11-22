<?php

namespace TitoMiguelCosta\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

use ZendService\Flickr\Flickr;
use Zend\Http\Client as HttpClient;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Image
{
    public function listAction(Application $app, $page = 1)
    {
        $httpClient = new HttpClient(Flickr::URI_BASE);
        $flickr = new Flickr(FLICKR_KEY, $httpClient);

        $images = $flickr->tagSearch('php');

        $response = new Response($app['twig']->render(
            'images/list.twig',
            array('images' => $images)
        ));

        $response->setPublic();
        $response->setExpires(new \DateTime('+1 day'));

        return $response;
    }
}
