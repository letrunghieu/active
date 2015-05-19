<?php

namespace HieuLe\Active;

use Illuminate\Routing\Router;
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
 * @version    2.1.0
 *
 */
class Active
{

    /**
     * Current router
     *
     * @var \Illuminate\Routing\Router
     */
    private $_router;

    public function __construct(Router $router)
    {
        $this->_router = $router;
    }

    /**
     * Return 'active' class if current requested URI is matched
     *
     * @param string $uri
     * @param string $activeClass
     * @param string $inactiveClass
     * @return string
     */
    public function uri($uri, $activeClass = 'active', $inactiveClass = '')
    {
        $currentRequest = $this->_router->getCurrentRequest();

        if (!$currentRequest)
        {
            return $inactiveClass;
        }

        if ($currentRequest->getPathInfo() == $uri)
        {
            return $activeClass;
        }

        return $inactiveClass;
    }
    /**
     * Return 'active' class if current requested query string has key that matches value
     *
     * @param string $key the query key
     * @param string $value the value of the query parameter
     * @param string $activeClass the returned class
     * @param string $inactiveClass
     * @return string the returned class if the parameter <code>$key</code> has
     * the value equal to <code>$value</code> or contains the <code>$value</code>
     * in case of an array
     */
    public function query($key, $value, $activeClass = 'active', $inactiveClass = '')
    {
        $currentRequest = $this->_router->getCurrentRequest();

        $queryValue = $currentRequest->query($key);

        if (($queryValue == $value) || (is_array($queryValue) && in_array($value, $queryValue)))
        {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Return 'active' class if current route match a pattern
     *
     * @param string|array $patterns
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function pattern($patterns, $activeClass = 'active', $inactiveClass = '')
    {
        $currentRequest = $this->_router->getCurrentRequest();

        if (!$currentRequest)
        {
            return $inactiveClass;
        }

        $uri = urldecode($currentRequest->path());

        if (!is_array($patterns))
        {
            $patterns = array($patterns);
        }

        foreach ($patterns as $p)
        {
            if (str_is($p, $uri))
            {
                return $activeClass;
            }
        }

        return $inactiveClass;
    }

    /**
     * Return 'active' class if current route name match one of provided names
     *
     * @param string|array $names
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    public function route($names, $activeClass = 'active', $inactiveClass = '')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName)
        {
            return $inactiveClass;
        }

        if (!is_array($names))
        {
            $names = array($names);
        }

        if (in_array($routeName, $names))
        {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Check the current route name with one or some patterns
     *
     * @param string|array $patterns
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string the <code>$activeClass</code> if matched
     * @since 1.2
     */
    public function routePattern($patterns, $activeClass = 'active', $inactiveClass = '')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName)
        {
            return $inactiveClass;
        }

        if (!is_array($patterns))
        {
            $patterns = array($patterns);
        }

        foreach ($patterns as $p)
        {
            if (str_is($p, $routeName))
            {
                return $activeClass;
            }
        }

        return $inactiveClass;
    }

    /**
     * Return 'active' class if current route action match one of provided action names
     *
     * @param string|array $actions
     * @param string $activeClass
     * @param string $inactiveClass
     * @param bool $fullClassName if set to false, only controller class name (without namespace) is included in the action string. Otherwise, namespace will be included.
     *
     * @return string
     */
    public function action($actions, $activeClass = 'active', $fullClassName = false, $inactiveClass = '')
    {
        if (!$fullClassName)
        {
            $routeExploded = explode('\\', $this->_router->currentRouteAction());
            $routeAction = end($routeExploded );
        }
        else
        {
            $routeAction = $this->_router->currentRouteAction();
        }

        if (!is_array($actions))
        {
            $actions = array($actions);
        }

        if (in_array($routeAction, $actions))
        {
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
     * @param array $excludedMethods
     *
     * @return string
     */
    public function controller($controller, $activeClass = 'active', $excludedMethods = array(), $inactiveClass = '')
    {
        $currentController = $this->getController();

        if ($currentController !== $controller)
        {
            return $inactiveClass;
        }

        $currentMethod = $this->getMethod();

        if (in_array($currentMethod, $excludedMethods))
        {
            return $inactiveClass;
        }

        return $activeClass;
    }

    /**
     * Return 'active' class if current controller name match one of provided
     * controller names.
     *
     * @param array $controllers
     * @param string $activeClass
     * @param string $inactiveClass
     * @return string
     */
    public function controllers(array $controllers, $activeClass = 'active', $inactiveClass = '')
    {
        $currentController = $this->getController();

        if (in_array($currentController, $controllers))
        {
            return $activeClass;
        }

        return $inactiveClass;
    }

    /**
     * Get the current controller name with the suffix 'Controller' trimmed
     *
     * @return string|null
     */
    public function getController()
    {
        $action = $this->_router->currentRouteAction();

        if ($action)
        {
            $extractedController = head(Str::parseCallback($action, null));
            // Trim the "Controller" word if it is the last word
            return preg_replace('/^(.+)(Controller)$/', '${1}', $extractedController);
        }

        return null;
    }

    /**
     * Get the current method name with the prefix 'get', 'post', 'put', 'delete', 'show' trimmed
     *
     * @return string|null
     */
    public function getMethod()
    {
        $action = $this->_router->currentRouteAction();

        if ($action)
        {
            $extractedController = last(Str::parseCallback($action, null));
            // Trim the "show", "post", "put", "delete", "get" if this is the
            // prefix of the method name
            return $extractedController ? preg_replace('/^(show|get|put|delete|post)(.+)$/', '${2}', $extractedController) : null;
        }

        return null;
    }

}
