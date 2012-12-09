<?php

namespace TitoMiguelCosta\Email;

class Exception extends \Swift_Message
{

    private $message;

    public function __construct($app, $exception)
    {
        $this->message = $app['twig']->render('email/exception.twig', array('exception' => $exception));

        parent::__construct('Tito Miguel Costa @ Exception', $this->message, 'text/html', 'utf-8');

        $this
            ->setFrom('exception@titomiguelcosta.com')
            ->setTo('exception@titomiguelcosta.com', 'Tito Miguel Costa');
    }

    public function generateId()
    {
        return md5($this->message);
    }

}