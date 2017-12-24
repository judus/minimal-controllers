<?php namespace Maduser\Minimal\Controllers\Factories;

use Maduser\Minimal\Controllers\Factories\AbstractFactory;
use Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface;
use Maduser\Minimal\Framework\Facades\IOC;

class ModelFactory extends AbstractFactory implements ModelFactoryInterface
{
    public function create(array $params = null, $class = null, $model = null)
    {
        !$model || $params[] = $model;

        // Do we have a provider? We're finished if true
        // TODO: find out why $class is not always a string
        if (is_string($class) && IOC::registered($class)) {
            return IOC::resolve($class, $params);
        }

        return IOC::make($class, $params);

    }
}