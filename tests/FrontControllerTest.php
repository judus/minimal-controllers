<?php

namespace Maduser\Minimal\Controllers\Tests;

use Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException;
use Maduser\Minimal\Controllers\Exceptions\UndefinedControllerException;
use Maduser\Minimal\Controllers\Factories\ControllerFactory;
use Maduser\Minimal\Controllers\FrontController;
use Maduser\Minimal\Routing\Route;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase
{
    public function testConstructor()
    {
        $fc = new FrontController(new ControllerFactory());
    }

    public function testSetAndGetRoute()
    {
        $fc = new FrontController(new ControllerFactory());
        $route = new Route();
        $fc->setRoute($route);
        $result = $fc->getRoute();
        $expected = $route;

        $this->assertEquals($expected, $route);
    }

    public function testSetAndGetController()
    {
        $fc = new FrontController(new ControllerFactory());
        $controller = new \stdClass();
        $fc->setController($controller);
        $result = $fc->getController();
        $expected = $controller;

        $this->assertEquals($expected, $result);
    }

    public function testSetAndGetControllerResult()
    {
        $fc = new FrontController(new ControllerFactory());
        $fc->setControllerResult([123, 456]);
        $result = $fc->getControllerResult();
        $expected = [123, 456];

        $this->assertEquals($expected, $result);
    }

    public function testSetAndGetResult()
    {
        $fc = new FrontController(new ControllerFactory());
        $fc->setResult([123, 456]);
        $result = $fc->getResult();
        $expected = [123, 456];

        $this->assertEquals($expected, $result);
    }

    public function testGetResultFromControllerResult()
    {
        $fc = new FrontController(new ControllerFactory());
        $fc->setControllerResult([123, 456]);
        $result = $fc->getResult();
        $expected = [123, 456];

        $this->assertEquals($expected, $result);
    }

    public function testExecuteMethod()
    {
        $fc = new FrontController(new ControllerFactory());
        $class = new DummyClass();
        $method = 'dummy';
        $params = [123, 456];

        $result = $fc->executeMethod($class, $method, $params);

        $expected = '123456';

        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException \Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException
     */
    public function testExecuteMethodThrowsMethodNotExists()
    {
        $fc = new FrontController(new ControllerFactory());
        $class = new DummyClass();
        $method = 'nonExistantMethod';

        $fc->executeMethod($class, $method);
    }

    public function testHandleController()
    {
        $fc = new FrontController(new ControllerFactory());
        $controller = new DummyClass();
        $action = 'dummy';
        $params = [123, 456];

        $fc->handleController($controller, $action, $params);

        $this->assertEquals($controller, $fc->getController());
        $this->assertEquals('123456', $fc->getControllerResult());
        $this->assertEquals('123456', $fc->getResult());
    }

    public function testDispatchController()
    {
        $fc = new FrontController(new ControllerFactory());

        $route = new Route([
            'controller' => DummyClass::class,
            'action' => 'dummy',
            'params' => [123, 456]
        ]);

        $fc->setRoute($route)->dispatchController();

        $this->assertEquals(new DummyClass, $fc->getController());
        $this->assertEquals('123456', $fc->getControllerResult());
        $this->assertEquals('123456', $fc->getResult());
    }

    /**
     * @expectedException \Maduser\Minimal\Controllers\Exceptions\UndefinedControllerException
     */
    public function testDispatchControllerThrowUndefinedController()
    {
        $fc = new FrontController(new ControllerFactory());

        $route = new Route([
            'action' => 'dummy',
            'params' => [123, 456]
        ]);

        $fc->setRoute($route)->dispatchController();
    }

    public function testDispatch()
    {
        $fc = new FrontController(new ControllerFactory());

        $route = new Route([
            'controller' => DummyClass::class,
            'action' => 'dummy',
            'params' => [123, 456]
        ]);

        $fc->dispatch($route);

        $this->assertEquals(new DummyClass, $fc->getController());
        $this->assertEquals('123456', $fc->getControllerResult());
        $this->assertEquals('123456', $fc->getResult());
    }

    public function testDispatchRouteWithClosure()
    {
        $fc = new FrontController(new ControllerFactory());

        $route = new Route([
            'closure' => function($a, $b) {
                return $a . $b;
            },
            'params' => [123, 456]
        ]);

        $fc->dispatch($route);

        $this->assertEquals('123456', $fc->getControllerResult());
        $this->assertEquals('123456', $fc->getResult());
    }
}

class DummyClass
{
    public function dummy($a, $b)
    {
        return $a . $b;
    }
}