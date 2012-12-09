<?php

namespace TitoMiguelCosta\Event;

use Silex\Application;
use Symfony\Component\EventDispatcher\Event;

class Exception extends Event
{
    const EXCEPTION_RAISED = 'exception.raised';

    protected $app;
    protected $exception;

    public function __construct(Application $app, \Exception $exception)
    {
        $this->app = $app;
        $this->exception = $exception;
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function getException()
    {
        return $this->exception;
    }

}