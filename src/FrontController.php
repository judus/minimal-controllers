<?php namespace Maduser\Minimal\Controllers;

use Maduser\Minimal\Controllers\Contracts\FrontControllerInterface;
use Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException;
use Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface;
use Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface;
use Maduser\Minimal\Controllers\Factories\Contracts\ViewFactoryInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;
use Maduser\Minimal\Routing\Contracts\RouterInterface;


/**
 * Class FrontController
 *
 * @package Maduser\Minimal\Core
 */
class FrontController implements FrontControllerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ResponseInterface
     */
    protected $modelFactory;

    /**
     * @var ResponseInterface
     */
    protected $viewFactory;

    /**
     * @var ControllerFactoryInterface
     */
    protected $controllerFactory;

    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @var
     */
    private $model;

    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $controller;

    /**
     * @var
     */
    private $action;

    /**
     * @var
     */
    private $view;

    /**
     * @var
     */
    private $result;

    /**
     * @var
     */
    private $modelResult;

    /**
     * @var
     */
    private $controllerResult;

    /**
     * @var
     */
    private $viewResult;

    /**
     * @var
     */
    private $params;

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @param \Closure $function
     *
     * @return mixed
     */
    public function getResult(\Closure $function = null)
    {
        if (is_callable($function)) {
            return $function();
        }

        return ((!empty($this->viewResult)
            && !is_null($this->viewResult)
            && $this->viewResult !== false) ?
            $this->viewResult :
            (!empty($this->controllerResult)
                && !is_null($this->controllerResult)
                && $this->controllerResult !== false) ?
                $this->controllerResult : null);
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
    public function getModelResult()
    {
        return $this->modelResult;
    }

    /**
     * @param mixed $modelResult
     */
    public function setModelResult($modelResult)
    {
        $this->modelResult = $modelResult;
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
     * @return mixed
     */
    public function getViewResult()
    {
        return $this->viewResult;
    }

    /**
     * @param mixed $viewResult
     */
    public function setViewResult($viewResult)
    {
        $this->viewResult = $viewResult;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * FrontController constructor.
     *
     * @param RouterInterface            $router
     * @param ResponseInterface          $response
     * @param ModelFactoryInterface      $modelFactory
     * @param ViewFactoryInterface       $viewFactory
     * @param ControllerFactoryInterface $controllerFactory
     */
    public function __construct(
        RouterInterface $router,
        ResponseInterface $response,
        ModelFactoryInterface $modelFactory,
        ViewFactoryInterface $viewFactory,
        ControllerFactoryInterface $controllerFactory
    ) {
        /** @var \Maduser\Minimal\Providers\RouterProvider $router */
        $this->router = $router;
        $this->response = $response;
        $this->modelFactory = $modelFactory;
        $this->viewFactory = $viewFactory;
        $this->controllerFactory = $controllerFactory;
        $this->route = $this->router->getRoute();
    }

    /**
     * @param            $model
     * @param null       $method
     * @param array|null $params
     */
    public function handleModel($model, $method = null, array $params = null)
    {
        $this->setModel(
            $this->modelFactory->create($params, $model)
        );

        if (!is_null($method)) {
            $this->setModelResult(
                $this->executeMethod($this->getModel(), $method, $params)
            );
        }

    }

    /**
     * @param            $view
     * @param null       $method
     * @param array|null $params
     */
    public function handleView($view, $method = null, array $params = null)
    {
        $this->setView(
            $this->viewFactory->create($params, $view)
        );

        if (!is_null($method)) {
            $this->setViewResult(
                $this->executeMethod($this->getView(), $method)
            );
        }

    }

    /**
     * @param            $controller
     * @param null       $action
     * @param array|null $params
     */
    public function handleController(
        $controller,
        $action = null,
        array $params = null
    ) {

        $this->setController(
            $this->controllerFactory->create($params, $controller)
        );

        if (!is_null($action)) {
            $this->setControllerResult(
                $this->executeMethod($this->getController(), $action, $params)
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
    public function executeMethod($class, $method, array $params = null)
    {
        if (!method_exists($class, $method)) {
            throw new MethodNotExistsException(
                "Method '" . $method . "' does not exist in "
                . get_class($class)
            );
        }

        $params = $params ? $params : [];

        return call_user_func_array([$class, $method], $params);
    }

    public function dispatchController()
    {
        if (!empty($this->route->getController())) {
            $this->handleController(
                $this->route->getController(),
                $this->route->getAction(),
                $this->route->getParams()
            );
        };
    }

    public function dispatchModel()
    {
        if (!empty($this->route->getModel())) {
            $this->handleModel(
                $this->route->getModel(),
                $this->route->getMethod(),
                $this->route->getParams()
            );
        };
    }

    public function dispatchView()
    {
        if (!empty($this->route->getView())) {
            $this->handleView(
                $this->route->getView(),
                $this->route->getMethod(),
                $this->route->getParams()
            );
        };
    }

    /**
     * @param RouteInterface|null $route
     * @param \Closure            $function
     *
     * @return $this
     */
    public function dispatch(RouteInterface $route = null, \Closure $function = null)
    {
        /** @var RouteInterface $route */
        is_null($route) || $this->setRoute($route);

        if (is_callable($function)) {

            $this->setControllerResult($function($this));

        } elseif ($route->hasClosure()) {
            $this->setControllerResult(call_user_func_array(
                $route->getClosure(), $route->getParams())
            );

        } else {

            $this->dispatchController();
            $this->dispatchModel();
            $this->dispatchView();

        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function respond()
    {
        $this->response->setContent($this->getResult())->send();

        return $this;
    }

    /**
     *
     */
    public function terminate()
    {
        $this->response->terminate();
    }
}
