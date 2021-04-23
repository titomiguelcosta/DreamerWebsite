<?php

namespace TitoMiguelCosta\Email;

use Exception as BaseException;
use Silex\Application;
use Swift_Message;

class Exception extends Swift_Message
{
    private $message;

    public function __construct(Application $app, BaseException $exception)
    {
        $this->message = $app['twig']->render('email/exception.twig', array('exception' => $exception, 'class' => get_class($exception)));

        parent::__construct('Tito Miguel Costa @ Exception', $this->message, 'text/html', 'utf-8');

        $this
            ->setFrom('titomiguelcosta@titomiguelcosta.com')
            ->setTo('exception@titomiguelcosta.com', 'Tito Miguel Costa');
    }

    public function generateId()
    {
        return md5($this->message);
    }
}
