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

    public function route($names, $class = 'active')
    {
	$routeName = $this->_route->getName();
	if (!is_array($names))
	    $names = array($names);
	if (in_array($routeName, $names))
	    return $class ? $class : 'active';
	return '';
    }

}

