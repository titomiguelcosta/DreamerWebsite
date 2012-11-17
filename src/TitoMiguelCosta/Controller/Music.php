<?php

namespace TitoMiguelCosta\Controller;

use ZendGData\YouTube;
use Zend\Http\Client;
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
        $service = \ZendGData\YouTube::AUTH_SERVICE_NAME;
        $adapter = new \Zend\Http\Client\Adapter\Curl();
        $httpClient = new \ZendGData\HttpClient();
        $httpClient->setAdapter($adapter);
        $client = \ZendGData\AuthSub::getHttpClient();


        $yt = new YouTube($client, 'Tito Miguel Costa', '3889946677.apps.googleusercontent.com', 'AI39si5q_CDI0h2XJG2xlrIpzMDR-7L9Vx50-6gUEbHKPKnZ_nO9DzL8x8Mll6DOLn9cmBulApMJACKOviOOMpqeU8VsjwgMuQ');
        $yt->setMajorProtocolVersion(2);
        $videoEntry = $yt->getVideoEntry('the0KZLEacs');
        echo 'Video: ' . $videoEntry->getVideoTitle() . "\n";
        echo 'Video ID: ' . $videoEntry->getVideoId() . "\n";
        echo 'Updated: ' . $videoEntry->getUpdated() . "\n";
        echo 'Description: ' . $videoEntry->getVideoDescription() . "\n";
        echo 'Category: ' . $videoEntry->getVideoCategory() . "\n";
        echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "\n";
        echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "\n";
        echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "\n";
        echo 'Duration: ' . $videoEntry->getVideoDuration() . "\n";
        echo 'View count: ' . $videoEntry->getVideoViewCount() . "\n";
        echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "\n";
        echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "\n";
        echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "\n";

        die();

        return $app['twig']->render(
            'music/list.twig',
            array('videos' => $videos)
        );
    }
}
