<?php

namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

class HttpDispatcher extends AbstractDispatcher
{
    public function getClosureSignature(RouteInterface $route): array
    {
        $signature = [];

        if (!empty($route->getModel())) {
            array_push($signature, App::make($route->getModel()));
        }

        $params = $route->getParams();
        count($params) > 0 && array_push($signature, $params);

        return $signature;
    }

    public function getClassSignature(RouteInterface $route): array
    {
        $signature = [];

        if (!empty($route->getModel())) {
            array_push($signature, App::make($route->getModel()));
        }

        $params = $route->getParams();
        count($params) > 0 && array_push($signature, $params);

        return $signature;
    }

    public function getMethodSignature(RouteInterface $route): array
    {
        $signature = [];

        $params = $route->getParams();

        if (count($params) == 0) return $signature;

        foreach ($params as $param) {
            array_push($signature, $param);
        }

        return $signature;
    }
}