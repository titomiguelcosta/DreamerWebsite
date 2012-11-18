<?php

namespace TitoMiguelCosta\Controller;

use Silex\Application;
use ZendGData\AuthSub;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Main
{

    public function youtubeAuthAction(Application $app)
    {
        $next = $app['url_generator']->generate('youtube_token', array(), true);
        $scope = 'http://gdata.youtube.com';
        $secure = false;
        $session = true;

        return $app->redirect(AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session));
    }

    public function youtubeTokenAction(Application $app)
    {
        if (!isset($_SESSION['sessionToken']) && !isset($_GET['token']))
        {
            $this->authAction($app);
        }
        else if (!isset($_SESSION['sessionToken']) && isset($_GET['token']))
        {
            $client = AuthSub::getHttpClient($_GET['token']);
            $client->setOptions(array('sslverifypeer' => false));
            die('Session: ' .AuthSub::getAuthSubSessionToken($_GET['token'], $client).'END');
        }

        die('You where supposte to have a token by now');
        //$httpClient = AuthSub::getHttpClient($_SESSION['sessionToken']);
        //return $httpClient;
    }

}
