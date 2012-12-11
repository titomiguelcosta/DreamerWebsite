<?php

namespace TitoMiguelCosta\Controller;

use ZendGData\YouTube;
use ZendGData\AuthSub;
use ZendGData\Query;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullIterator;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Music
{
    public function listAction(Application $app, $page = 1)
    {
        $client = AuthSub::getHttpClient(YOUTUBE_TOKEN);
        $client->setOptions(array('sslverifypeer' => false));

        $yt = new YouTube($client, null, null, YOUTUBE_DEVELOPER_KEY);
        $yt->setMajorProtocolVersion(2);

        $max_per_page = 4;
        $query = new Query('https://gdata.youtube.com/feeds/api/playlists/PL3E4772C48425800C');
        $query->setMaxResults($max_per_page);
        $query->setStartIndex(($page-1) * $max_per_page + 1);

        $videos = $yt->getPlaylistVideoFeed($query);

        $total = (int) $videos->getTotalResults()->__toString();

        $paginator = new Paginator(new NullIterator($total));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($query->getMaxResults());

        $response = new Response($app['twig']->render(
            'music/list.twig',
            array('videos' => $videos, 'pages' => $paginator->getPages(), 'route' => 'music')
        ));

        $response->setPublic();
        $response->setExpires(new \DateTime('+1 day'));

        return $response;
    }
}
