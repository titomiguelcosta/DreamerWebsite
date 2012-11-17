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

    public function authAction(Application $app)
    {
        $next = $app['url_generator']->generate('token', array(), true);
        $scope = 'http://gdata.youtube.com';
        $secure = false;
        $session = true;

        return $app->redirect(AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session));
    }

    public function tokenAction(Application $app)
    {
        if (!isset($_SESSION['sessionToken']) && !isset($_GET['token']))
        {
            $this->authAction($app);
        }
        else if (!isset($_SESSION['sessionToken']) && isset($_GET['token']))
        {
            die(AuthSub::getAuthSubSessionToken($_GET['token']));
        }

        die('You where supposte to have a token by now');
        //$httpClient = AuthSub::getHttpClient($_SESSION['sessionToken']);
        //return $httpClient;
    }

}
