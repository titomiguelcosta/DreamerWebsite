<?php
namespace TitoMiguelCosta\Listener;

use TitoMiguelCosta\Event\Work as EventWork;
use TitoMiguelCosta\Email\Work as EmailWork;

class Work
{
//    public static function db(EventWork $event)
//    {
//        $app = $event->getApplication();
//        $app['db']->insert('work', array_merge($event->getData(), array('created_at' => time())));
//    }
    public static function email(EventWork $event)
    {
        $app = $event->getApplication();
        $app['mailer']->send(new EmailWork($app, $event->getData()));
    }
}
