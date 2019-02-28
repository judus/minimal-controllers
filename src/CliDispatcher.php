<?php

namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

class CliDispatcher extends AbstractDispatcher
{
    public function getClosureSignature(RouteInterface $route): array
    {
        $signature = [];

        if (!empty($route->getModel())) {
            $signature = array_merge($signature, App::make($route->getModel()));
        }

        $arguments = $this->getArguments($route);
        count($arguments) > 0 && $signature = array_merge($signature, $arguments);

        $params = $route->getParams();
        count($params) > 0 && $signature = array_merge($signature, $params);

        $options = $this->getOptions($route);
        count($options) > 0 && $signature = array_merge($signature, $options);

        return $signature;
    }

    public function getClassSignature(RouteInterface $route): array
    {
        $signature = [];

        if (!empty($route->getModel())) {
            $signature = array_merge($signature, App::make($route->getModel()));
        }

        $options = $this->getOptions($route);
        count($options) > 0 && array_push($signature, $options);

        return $signature;
    }

    public function getMethodSignature(RouteInterface $route): array
    {
        $signature = [];

        $arguments = $this->getArguments($route);

        count($arguments) > 0 && $signature = array_merge($signature, $arguments);

        $params = $route->getParams();
        count($params) > 0 && $signature = array_merge($signature, $params);

        return $signature;
    }
}