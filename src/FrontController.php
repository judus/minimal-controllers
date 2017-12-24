<?php namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Controllers\Contracts\FrontControllerInterface;
use Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException;
use Maduser\Minimal\Controllers\Exceptions\UndefinedControllerException;
use Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface;
use Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

/**
 * Class FrontController
 *
 * @package Maduser\Minimal\Controllers
 */
class FrontController implements FrontControllerInterface
{
    /**
     * @var
     */
    private $controller;

    /**
     * @var ControllerFactoryInterface
     */
    private $controllerFactory;

    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;

    /**
     * @var
     */
    private $controllerResult;

    /**
     * @var
     */
    private $result;

    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     *
     * @return FrontControllerInterface
     */
    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return !is_null($this->result) ?
            $this->result : $this->getControllerResult();
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getControllerResult()
    {
        return $this->controllerResult;
    }

    /**
     * @param mixed $controllerResult
     */
    public function setControllerResult($controllerResult)
    {
        $this->controllerResult = $controllerResult;
    }

    /**
     * FrontController constructor.
     *
     * @param ControllerFactoryInterface $controllerFactory
     * @param ModelFactoryInterface $modelFactory
     */
    public function __construct(
        ControllerFactoryInterface $controllerFactory,
        ModelFactoryInterface $modelFactory = null
    ) {
        $this->controllerFactory = $controllerFactory;
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param            $controller
     * @param null       $action
     * @param array|null $params
     *
     * @throws MethodNotExistsException
     */
    public function handleController(
        $controller, $action = null, array $params = null, $model = null
    ) {

        ! $model || $model = $this->modelFactory->create(null, $model);

        $this->setController(
            $this->controllerFactory->create($params, $controller, $model)
        );

        if (!is_null($action)) {
            $this->setControllerResult(
                $this->executeMethod($this->getController(), $action, $params, $model)
            );
        }
    }

    /**
     * @param            $class
     * @param            $method
     * @param array|null $params
     *
     * @return mixed
     * @throws MethodNotExistsException
     */
    public function executeMethod($class, $method, array $params = null, $model = null)
    {
        if (!method_exists($class, $method)) {
            throw new MethodNotExistsException(
                "Method '" . $method . "' does not exist in ". get_class($class)
            );
        }

        $params = $params ? $params : [];
        ! $model || $params[] = $model;

        return call_user_func_array([$class, $method], $params);
    }


    public function execute($class, $method, array $params = null)
    {
        $this->handleController($class, $method, $params);

        return $this->getControllerResult();
    }

    public function dispatchController()
    {
        if (empty($this->getRoute()->getController())) {
            throw new UndefinedControllerException(
                'This route seems to have no controller attached to it'
            );
        };

        $this->handleController(
            $this->getRoute()->getController(),
            $this->getRoute()->getAction(),
            $this->getRoute()->getParams(),
            $this->getRoute()->getModel()
        );
    }

    /**
     * @param RouteInterface $route
     *
     * @return $this
     */
    public function dispatch(RouteInterface $route)
    {
        $this->setRoute($route);

        if ($route->hasClosure()) {
            $this->setControllerResult(call_user_func_array(
                $route->getClosure(), $route->getParams()
            ));
        } else {
            $this->dispatchController();
        }

        return $this;
    }

}
