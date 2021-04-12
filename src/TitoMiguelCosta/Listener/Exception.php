<?php

namespace TitoMiguelCosta\Listener;

use Interop\Container\Exception\NotFoundException;
use Silex\Application;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use TitoMiguelCosta\Event\Exception as EventException;
use TitoMiguelCosta\Email\Exception as EmailException;

class Exception
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handler(GetResponseForExceptionEvent $event)
    {
        $this->app['dispatcher']->dispatch(EventException::EXCEPTION_RAISED, new EventException($this->app, $event->getException()));

        $response = new Response($this->app['twig']->render('site/exception.twig', array()));
        $response->setStatusCode('500');
        $response->setTtl(86400);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    public static function email(EventException $event)
    {
        if ($event->getException() instanceof NotFoundException) {
            return;
        }

        $app = $event->getApplication();
        $app['mailer']->send(new EmailException($event->getApplication(), $event->getException()));
    }
}
