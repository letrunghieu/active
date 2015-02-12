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
 *   <li>curernt controller</li>
 * </ul>
 * 
 * @package    HieuLe\Active
 * @author     Hieu Le <letrunghieu.cse09@gmail.com>
 * @version    1.2.2
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
     * @param string $class
     * @return string
     */
    public function uri($uri, $class = 'active')
    {
        $currentRequest = $this->_router->getCurrentRequest();

        if (!$currentRequest)
        {
            return '';
        }

        if ($currentRequest->getPathInfo() == $uri)
        {
            return $class;
        }

        return '';
    }

    /**
     * Return 'active' class if current route match a pattern
     * 
     * @param string|array $patterns
     * @param string $class
     * 
     * @return string
     */
    public function pattern($patterns, $class = 'active')
    {
        $currentRequest = $this->_router->getCurrentRequest();

        if (!$currentRequest)
        {
            return '';
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
                return $class;
            }
        }

        return '';
    }

    /**
     * Return 'active' class if current route name match one of provided names
     * 
     * @param string|array $names
     * @param string $class
     * 
     * @return string
     */
    public function route($names, $class = 'active')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName)
        {
            return '';
        }

        if (!is_array($names))
        {
            $names = array($names);
        }

        if (in_array($routeName, $names))
        {
            return $class;
        }

        return '';
    }

    /**
     * Check the current route name with one or some patterns
     * 
     * @param string|array $patterns
     * @param string $class
     * 
     * @return string the <code>$class</code> if matched
     * @since 1.2
     */
    public function routePattern($patterns, $class = 'active')
    {
        $routeName = $this->_router->currentRouteName();

        if (!$routeName)
        {
            return '';
        }

        if (!is_array($patterns))
        {
            $patterns = array($patterns);
        }

        foreach ($patterns as $p)
        {
            if (str_is($p, $routeName))
            {
                return $class;
            }
        }

        return '';
    }

    /**
     * Return 'active' class if current route action match one of provided action names
     * 
     * @param string|array $actions
     * @param string $class
     * 
     * @return string
     */
    public function action($actions, $class = 'active')
    {
        $routeAction = $this->_router->currentRouteAction();

        if (!is_array($actions))
        {
            $actions = array($actions);
        }

        if (in_array($routeAction, $actions))
        {
            return $class;
        }

        return '';
    }

    /**
     * Return 'active' class if current controller match a controller name and 
     * current method doest not belong to excluded methods. The controller name 
     * and method name are gotten from <code>getController</code> and <code>getMethod</code>.
     * 
     * @param string $controller
     * @param string $class
     * @param array $excludedMethods
     * 
     * @return string
     */
    public function controller($controller, $class = 'active', $excludedMethods = array())
    {
        $currentController = $this->getController();

        if ($currentController !== $controller)
        {
            return '';
        }

        $currentMethod = $this->getMethod();

        if (in_array($currentMethod, $excludedMethods))
        {
            return '';
        }

        return $class;
    }

    /**
     * Return 'active' class if current controller name match one of provided
     * controller names.
     * 
     * @param array $controllers
     * @param string $class
     * @return string
     */
    public function controllers(array $controllers, $class = 'active')
    {
        $currentController = $this->getController();

        if (in_array($currentController, $controllers))
        {
            return $class;
        }

        return '';
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
