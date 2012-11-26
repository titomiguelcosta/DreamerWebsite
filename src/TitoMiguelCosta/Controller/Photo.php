<?php

namespace TitoMiguelCosta\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use TitoMiguelCosta\Flickr\Flickr;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullIterator;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Photo
{
    public function listAction(Application $app, $page = 1, $per_page = 10)
    {
        $flickr = new Flickr(FLICKR_KEY);

        $images = $flickr->userGallery('titomiguelcosta', '5035846-72157632069326521', array(
            'per_page' => $per_page,
            'page' => (int) $page
        ));

//        $images = $flickr->tagSearch('chicken', array(
//            'per_page' => $per_page,
//            'page' => (int) $page
//        ));

//        $images = $flickr->userSearch('titomiguelcosta', array(
//            'per_page' => $per_page,
//            'page' => (int) $page
//        ));

        $paginator = new Paginator(new NullIterator($images->totalResults()));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($per_page);

        $response = new Response($app['twig']->render(
            'photos/list.twig',
            array('images' => $images, 'pages' => $paginator->getPages(), 'route' => 'photos')
        ));

        $response->setPublic();
        $response->setExpires(new \DateTime('+1 day'));

        return $response;
    }
}
