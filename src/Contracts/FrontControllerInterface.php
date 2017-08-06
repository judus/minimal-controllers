<?php namespace Maduser\Minimal\Controllers\Contracts;

use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;
use Maduser\Minimal\Routing\Contracts\RouterInterface;

/**
 * Interface FrontControllerInterface
 *
 * @package Maduser\Minimal\Interfaces
 */
interface FrontControllerInterface
{
	/**
	 * @return RouterInterface
	 */
	public function getRouter() : RouterInterface;

	/**
	 * @param RouterInterface $router
	 */
	public function setRouter(RouterInterface $router);

	/**
	 * @return ResponseInterface
	 */
	public function getResponse() : ResponseInterface;

	/**
	 * @param ResponseInterface $response
	 */
	public function setResponse(ResponseInterface $response);

	/**
	 * @return mixed
	 */
	public function getRoute();

	/**
	 * @param mixed $route
	 */
	public function setRoute($route);

	/**
	 * @return mixed
	 */
	public function getModel();

	/**
	 * @param mixed $model
	 */
	public function setModel($model);

	/**
	 * @return mixed
	 */
	public function getMethod();

	/**
	 * @param mixed $method
	 */
	public function setMethod($method);

	/**
	 * @return mixed
	 */
	public function getController();

	/**
	 * @param mixed $controller
	 */
	public function setController($controller);

	/**
	 * @return mixed
	 */
	public function getAction();

	/**
	 * @param mixed $action
	 */
	public function setAction($action);

	/**
	 * @return mixed
	 */
	public function getView();

	/**
	 * @param mixed $view
	 */
	public function setView($view);

    /**
     * @param \Closure $function
     *
     * @return mixed
     */
	public function getResult(\Closure $function = null);

	/**
	 * @param mixed $result
	 */
	public function setResult($result);

	/**
	 * @return mixed
	 */
	public function getModelResult();

	/**
	 * @param mixed $modelResult
	 */
	public function setModelResult($modelResult);

	/**
	 * @return mixed
	 */
	public function getControllerResult();

	/**
	 * @param mixed $controllerResult
	 */
	public function setControllerResult($controllerResult);

	/**
	 * @return mixed
	 */
	public function getViewResult();

	/**
	 * @param mixed $viewResult
	 */
	public function setViewResult($viewResult);

	/**
	 * @return mixed
	 */
	public function getParams();

	/**
	 * @param mixed $params
	 */
	public function setParams($params);

    /**
     * @param RouteInterface $route
     * @param \Closure       $function
     *
     * @return
     */
	public function dispatch(RouteInterface $route, \Closure $function = null);

	/**
	 * @return mixed
	 */
	public function respond();
}