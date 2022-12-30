<?php
namespace TitoMiguelCosta\Listener;

use TitoMiguelCosta\Event\Contact as EventContact;
use TitoMiguelCosta\Email\Contact as EmailContact;

class Contact
{
    public static function db(EventContact $event)
    {
        $app = $event->getApplication();
        // $app['db']->insert('contact', array_merge($event->getData(), array('created_at' => time())));
    }

    public static function email(EventContact $event)
    {
        $app = $event->getApplication();
        $app['mailer']->send(new EmailContact($app, $event->getData()));
    }
}
