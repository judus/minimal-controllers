<?php namespace Maduser\Minimal\Controllers\Factories;

use Maduser\Minimal\Controllers\Factories\Contracts\FactoryInterface;
use Maduser\Minimal\Framework\Facades\IOC;

/**
 * Class MinimalFactory
 *
 * @package Maduser\Minimal\Apps
 */
abstract class AbstractFactory implements FactoryInterface
{
    public function create(array $params = null, $class = null, $model = null)
    {
        ! $model || $params[] = $model;



        // Do we have a provider? We're finished if true
        // TODO: find out why $class is not always a string
        if (is_string($class) && IOC::registered($class)) {
            return IOC::resolve($class, $params);
        }

        return IOC::make($class, $params);
    }
}