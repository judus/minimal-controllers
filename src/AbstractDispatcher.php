<?php

namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Controllers\Contracts\TypedDispatcherInterface;
use Maduser\Minimal\Http\Contracts\RequestInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

class AbstractDispatcher implements TypedDispatcherInterface
{
    protected $request;

    protected $response;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function dispatch(RouteInterface $route)
    {
        if ($route->hasClosure()) {
            return $this->dispatchClosure($route);
        }

        return $this->dispatchHandler($route);
    }

    public function dispatchClosure(RouteInterface $route)
    {
        return call_user_func_array(
            $route->getClosure(), $this->getClosureSignature($route)
        );
    }

    public function dispatchHandler(RouteInterface $route)
    {
        return $this->execute(
            App::make($route->getController(), $this->getClassSignature($route)),
            $route->getAction(),
            $this->getMethodSignature($route)
        );
    }

    public function execute($class, $method, array $params = [])
    {
        if (!is_object($class)) {
            $class = App::make($class, $params);
        }
        return call_user_func_array([$class, $method], $params);
    }

    public function getClosureSignature(RouteInterface $route): array
    {
        return [];
    }

    public function getClassSignature(RouteInterface $route): array
    {
        return [];
    }

    public function getMethodSignature(RouteInterface $route): array
    {
        return [];
    }


    public function getOptions(RouteInterface $route): array
    {
        $options = $this->parseOptions();

        if (count($route->getOptions()) > 0) {
            $options = array_intersect_key($route->getOptions(), $options);
        }

        return $options;
    }

    public function parseOptions()
    {
        $args = $this->request->getArgs();

        $options = [];

        foreach ($args as $arg) {
            if (preg_match('/^--([^=]+)=(.*)/', $arg, $match)) {
                $options[$match[1]] = $match[2];
            } else {
                if (preg_match('/^--([^=]+)/', $arg, $match)) {
                    $options[$match[1]] = true;
                }
            }
        }

        return $options;
    }

    public function getArguments(RouteInterface $route): array
    {
        $arguments = $this->request->getArgs();

        array_shift($arguments);

        $count = count($route->getArguments());

        $arguments = array_slice($arguments, 0, $count);

        return $arguments;
    }

    public function getResponse($data)
    {
        if ($this->isResponse($data)) {
            return $data;
        }

        return $this->response->setContent($data);
    }

    public function isResponse($var)
    {
        if (is_object($var)) {
            return in_array(ResponseInterface::class, class_implements($var));
        }

        return false;
    }
}