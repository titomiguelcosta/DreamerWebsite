<?php

namespace TitoMiguelCosta\Email;

class Contact extends \Swift_Message
{

    private $message;

    public function __construct($app, $data)
    {
        $this->message = $app['twig']->render('email/contact.twig', array('data' => $data));

        parent::__construct('Tito Miguel Costa @ Contact', $this->message, 'text/html', 'utf-8');

        $this
            ->setFrom('titomiguelcosta@titomiguelcosta.com')
            ->setTo('contact@titomiguelcosta.com', 'Tito Miguel Costa');
    }

    public function generateId()
    {
        return md5($this->message);
    }
}
