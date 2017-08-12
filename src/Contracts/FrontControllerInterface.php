<?php
/**
 * FrontControllerInterface.php
 * 8/12/17 - 2:29 PM
 *
 * PHP version 7
 *
 * @package    @package_name@
 * @author     Julien Duseyau <julien.duseyau@gmail.com>
 * @copyright  2017 Julien Duseyau
 * @license    https://opensource.org/licenses/MIT
 * @version    Release: @package_version@
 *
 * The MIT License (MIT)
 *
 * Copyright (c) Julien Duseyau <julien.duseyau@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Maduser\Minimal\Controllers\Contracts;

use Maduser\Minimal\Controllers\Exceptions\MethodNotExistsException;
use Maduser\Minimal\Controllers\FrontController;
use Maduser\Minimal\Routing\Contracts\RouteInterface;


/**
 * Class FrontController
 *
 * @package Maduser\Minimal\Controllers
 */
interface FrontControllerInterface
{
    /**
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface;

    /**
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route);

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
    public function getResult();

    /**
     * @param mixed $result
     */
    public function setResult($result);

    /**
     * @return mixed
     */
    public function getControllerResult();

    /**
     * @param mixed $controllerResult
     */
    public function setControllerResult($controllerResult);

    /**
     * @param            $controller
     * @param null       $action
     * @param array|null $params
     */
    public function handleController(
        $controller,
        $action = null,
        array $params = null
    );

    /**
     * @param            $class
     * @param            $method
     * @param array|null $params
     *
     * @return mixed
     * @throws MethodNotExistsException
     */
    public function executeMethod($class, $method, array $params = null);

    public function dispatchController();

    /**
     * @param RouteInterface $route
     *
     * @return $this
     */
    public function dispatch(RouteInterface $route);
}