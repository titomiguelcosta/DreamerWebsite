<?php

namespace TitoMiguelCosta\Controller;

use ZendGData\YouTube;
use ZendGData\AuthSub;
use ZendGData\Query;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Silex\Application;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Music
{
    public function listAction(Application $app, $page = 1)
    {
        $client = AuthSub::getHttpClient('1/EZCzmMPZCLQ-16JG9LjmN1myfavTTrv_ncw0NYJuoh4');
        $client->setOptions(array('sslverifypeer' => false));

        //$yt = new YouTube($client, 'Tito Miguel Costa', '3889946677.apps.googleusercontent.com', 'AI39si5q_CDI0h2XJG2xlrIpzMDR-7L9Vx50-6gUEbHKPKnZ_nO9DzL8x8Mll6DOLn9cmBulApMJACKOviOOMpqeU8VsjwgMuQ');
        $yt = new YouTube($client);
        $yt->setMajorProtocolVersion(2);

        $query = new Query('https://gdata.youtube.com/feeds/api/playlists/PL3E4772C48425800C');
        $query->setMaxResults(4);
        $query->setStartIndex($page);

        $videos = $yt->getPlaylistVideoFeed($query);

        $total = (int) $videos->getTotalResults()->__toString();
        $array = range(1, $total);

        $paginator = new Paginator(new ArrayAdapter($array));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($query->getMaxResults());

        return $app['twig']->render(
            'music/list.twig',
            array('videos' => $videos, 'pages' => $paginator->getPages())
        );
    }
}
