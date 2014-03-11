<?php

namespace HieuLe\Active;

use Illuminate\Routing\Route;

/**
 * Description of Active
 *
 * @author TrungHieu
 */
class Active
{

    /**
     *
     * @var \Illuminate\Routing\Route
     */
    private $_route;

    public function __construct(Route $route)
    {
	$this->_route = $route;
    }

    /**
     * Return 'active' class if current route match a pattern
     * 
     * @param string|array $patterns
     * @param string $class
     * @return string
     */
    public function pattern($patterns, $class = 'active')
    {
	$uri = $this->_route->getUri();
	if (!is_array($patterns))
	    $patterns = array($patterns);
	foreach ($patterns as $p)
	{
	    if (str_is($p, $uri))
		return $class ? $class : 'active';
	}
	return '';
    }

    /**
     * Return 'active' class if current route name match one of provided names
     * 
     * @param string|array $names
     * @param string $class
     * @return string
     */
    public function route($names, $class = 'active')
    {
	$routeName = $this->_route->getName();
	if (!$routeName)
	    return '';
	if (!is_array($names))
	    $names = array($names);
	if (in_array($routeName, $names))
	    return $class ? $class : 'active';
	return '';
    }

    /**
     * Return 'active' class if current route action match one of provided action names
     * 
     * @param string|array $actions
     * @param string $class
     * @return string
     */
    public function action($actions, $class = 'active')
    {
	$routeAction = $this->_route->getActionName();
	if (!is_array($actions))
	    $actions = array($actions);
	if (in_array($routeAction, $actions))
	    return $class ? $class : 'active';
	return '';
    }

}

