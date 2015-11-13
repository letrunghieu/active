<?php

namespace HieuLe\Active;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

/**
 * Return "active" class for the current route if needed
 *
 * Check the current route to decide whether return an "active" class base on:
 * <ul>
 *   <li>current route URI</li>
 *   <li>current route name</li>
 *   <li>current action</li>
 *   <li>current controller</li>
 * </ul>
 *
 * @package    HieuLe\Active
 * @author     Hieu Le <letrunghieu.cse09@gmail.com>
 * @version    3.0.0
 *
 */
class Active
{

    /**
     * Current request
     *
     * @var Request
     */
    protected $request;

    /**
     * Current matched route
     *
     * @var Route
     */
    protected $route;

    /**
     * Current action string
     *
     * @var string
     */
    protected $action;

    /**
     * Current controller class
     *
     * @var string
     */
    protected $controller;

    /**
     * Current controller method
     *
     * @var string
     */
    protected $method;

    /**
     * Current URI
     *
     * @var string
     */
    protected $uri;

    /**
     * Active constructor.
     *
     * @param Request $request current request instance
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->uri = urldecode($request->path());
    }

    /**
     * Update the route and request instances
     *
     * @param Route   $route
     * @param Request $request
     */
    public function updateInstances(Route $route, Request $request)
    {
        $this->request = $request;
        $this->uri = urldecode($request->path());

        $this->$route = $route;
        $this->action = $route->getActionName();

        if ($this->action != 'Closure') {
            $actionSegments = Str::parseCallback($this->action);
            $this->controller = head($actionSegments);
            $this->method = last($actionSegments);
        } else {
            $this->controller = null;
            $this->method = null;
        }
    }

    /**
     * Return the active class if the current URI matches a specific value
     *
     * @param        $uri
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function ifUri($uri, $activeClass = 'active', $inactiveClass = '')
    {
        return $this->checkUri([$uri]) ? $activeClass : $inactiveClass;
    }

    /**
     * Return the active class if the current URI matches one of many specific values
     *
     * @param  array $uris
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function ifUriIn(array $uris, $activeClass = 'active', $inactiveClass = '')
    {
        return $this->checkUri($uris) ? $activeClass : $inactiveClass;
    }

    /**
     * Return 'active' class if current requested querystring has key that matches value.
     *
     * There are 3 cases that is considered as a matching:
     * + the value of $value is `false` and the current querystring contain the key $key
     * + the value of $value is not `false` and the current value of the $key key in the querystring equals to $value
     * + the value of $value is not `false` and the current value of the $key key in the querystring is an array that
     * contains the $value
     *
     *
     * @param string $key         the query key
     * @param string $value       the value of the query parameter
     * @param string $activeClass the returned class
     * @param string $inactiveClass
     *
     * @return string
     */
    public function ifQuery($key, $value, $activeClass = 'active', $inactiveClass = '')
    {
        return $this->checkQuery($key, $value) ? $activeClass : $inactiveClass;
    }

    /**
     * Return 'active' class if current route match a pattern
     *
     * @param string $pattern
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function ifUriPattern($pattern, $activeClass = 'active', $inactiveClass = '')
    {
        return $this->checkUriPattern([$pattern]) ? $activeClass : $inactiveClass;
    }

    /**
     * Return 'active' class if current route match one of specific patterns
     *
     * @param array  $patterns
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function ifUriPatternIn(array $patterns, $activeClass = 'active', $inactiveClass = '')
    {
        return $this->checkUriPattern($patterns) ? $activeClass : $inactiveClass;
    }

    /**
     * Return 'active' class if current route name match one of provided names
     *
     * @param string|array $names
     * @param string       $activeClass
     * @param string       $inactiveClass
     *
     * @return string
     */
    public function route($names, $activeClass = 'active', $inactiveClass = '')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName) {
            return $inactiveClass;
        }

        if (!is_array($names)) {
            $names = [$names];
        }

        if (in_array($routeName, $names)) {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Check the current route name with one or some patterns
     *
     * @param string|array $patterns
     * @param string       $activeClass
     * @param string       $inactiveClass
     *
     * @return string the <code>$activeClass</code> if matched
     * @since 1.2
     */
    public function routePattern($patterns, $activeClass = 'active', $inactiveClass = '')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName) {
            return $inactiveClass;
        }

        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }

        foreach ($patterns as $p) {
            if (str_is($p, $routeName)) {
                return $activeClass;
            }
        }

        return $inactiveClass;
    }

    /**
     * Check the current route parameters to see whether the value of that parameter matches a specific value
     *
     * @param string $param
     * @param mixed  $value
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     *
     * @since 3.0.0
     */
    public function routeParameter($param, $value, $activeClass = 'active', $inactiveClass = '')
    {
        $route = $this->_router->getCurrentRoute();

        if (!$route) {
            return $inactiveClass;
        }

        if (!$route->parameter($param) == $value) {
            return $inactiveClass;
        }

        return $activeClass;

    }

    /**
     * Return 'active' class if current route action match one of provided action names
     *
     * @param string|array $actions
     * @param string       $activeClass
     * @param string       $inactiveClass
     *
     * @return string
     */
    public function action($actions, $activeClass = 'active', $inactiveClass = '')
    {
        $routeAction = $this->_router->currentRouteAction();

        if (!is_array($actions)) {
            $actions = [$actions];
        }

        if (in_array($routeAction, $actions)) {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Return 'active' class if current controller match a controller name and
     * current method doest not belong to excluded methods. The controller name
     * and method name are gotten from <code>getController</code> and <code>getMethod</code>.
     *
     * @param string $controller
     * @param string $activeClass
     * @param string $inactiveClass
     * @param array  $excludedMethods
     *
     * @return string
     */
    public function controller($controller, $activeClass = 'active', $excludedMethods = [], $inactiveClass = '')
    {
        $currentController = $this->getController();

        if ($currentController !== $controller) {
            return $inactiveClass;
        }

        $currentMethod = $this->getMethod();

        if (in_array($currentMethod, $excludedMethods)) {
            return $inactiveClass;
        }

        return $activeClass;
    }

    /**
     * Get the current controller class
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller ?: "";
    }

    /**
     * Get the current controller method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method ?: "";
    }

    /**
     * Get the current action string
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action ?: "";
    }

    /**
     * Return 'active' class if current controller name match one of provided
     * controller names.
     *
     * @param array  $controllers
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function controllers(array $controllers, $activeClass = 'active', $inactiveClass = '')
    {
        $currentController = $this->getController();

        if (in_array($currentController, $controllers)) {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Check if the current URI matches one of specific patterns (using `str_is`)
     *
     * @param array $patterns
     *
     * @return bool
     */
    protected function checkUriPattern(array $patterns)
    {
        if (!$this->request) {
            throw new \RuntimeException('There is no instance of [' . Request::class . '] class.');
        }

        foreach ($patterns as $p) {
            if (str_is($p, $this->uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if one of the following condition is true:
     * + the value of $value is `false` and the current querystring contain the key $key
     * + the value of $value is not `false` and the current value of the $key key in the querystring equals to $value
     * + the value of $value is not `false` and the current value of the $key key in the querystring is an array that
     * contains the $value
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    protected function checkQuery($key, $value)
    {
        if (!$this->request) {
            throw new \RuntimeException('There is no instance of [' . Request::class . '] class.');
        }

        $queryValue = $this->request->query($key);

        // if the `key` exists in the query string with the correct value
        // OR it exists with any value
        // OR its value is an array that contains the specific value
        if (($queryValue == $value) || ($queryValue !== null && $value === false) || (is_array($queryValue) && in_array($value,
                    $queryValue))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if the URI of the current request matches one of the specific URIs
     *
     * @param array $uris
     *
     * @return bool
     */
    protected function checkUri(array $uris)
    {
        if (!$this->request) {
            throw new \RuntimeException('There is no instance of [' . Request::class . '] class.');
        }

        foreach ($uris as $uri) {
            if ($this->uri == $uri) {
                return true;
            }
        }

        return false;
    }


}
