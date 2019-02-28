<?php

namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Controllers\Contracts\FrontControllerInterface;
use Maduser\Minimal\Controllers\Contracts\TypedDispatcherInterface;
use Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException;
use Maduser\Minimal\Http\Contracts\RequestInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

class Dispatcher implements DispatcherInterface
{
    protected $dispatcher;

    public function __construct(TypedDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(RouteInterface $route)
    {
        return $this->dispatcher->dispatch($route);
    }

    public function execute($class, $method, array $params = null)
    {
        return $this->dispatcher->execute($class, $method, $params);
    }

}