<?php

namespace TitoMiguelCosta\Email;

class Contact extends \Swift_Message
{

    private $message;

    public function __construct($app, $data)
    {
        $this->message = $app['twig']->render('email/contact.twig', array('data' => $data));
        $this
            ->setSubject('Tito Miguel Costa @ Contact')
            ->setFrom(array($data['email']))
            ->setTo(array('contact@titomiguelcosta.com'))
            ->setBody($this->message);
    }

    public function generateId()
    {
        return md5($this->message);
    }

}