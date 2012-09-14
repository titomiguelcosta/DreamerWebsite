<?php

namespace TitoMiguelCosta\Email;

class Work extends \Swift_Message
{

    private $message;

    public function __construct($app, $data)
    {
        $this->message = $app['twig']->render('email/work.twig', array('data' => $data));

        parent::__construct('Tito Miguel Costa @ Work', $this->message, 'text/html', 'utf-8');

        $this
            ->setFrom($data['email'])
            ->setTo('work@titomiguelcosta.com', 'Tito Miguel Costa');
    }

    public function generateId()
    {
        return md5($this->message);
    }

}
