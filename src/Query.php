<?php

namespace HieuLe\Active;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class Query
{
    private $_action;
    private $_controller;
    private $_method;
    private $_params;
    private $_uri;

    private $_request;

    private $_result;

    /**
     * Query constructor.
     *
     * @param Route   $route
     * @param Request $request
     */
    public function __construct(Route $route, Request $request)
    {
        $this->_action = $route->getAction();
        if ($this->_action) {
            $actionSegments = Str::parseCallback($this->_action);
            $this->_controller = head($actionSegments);
            $this->_method = last($actionSegments);
        }

        $this->_params = $route->parameters();
        $this->_uri = $request->getUri();

        $this->_request = $request;
    }

    /**
     * Compare the current action with a string or a set of string. The result if true if the current action
     * matches one of them.
     *
     * @param string|array $actions
     *
     * @return Query
     */
    public function actions($actions)
    {
        if (!is_array($actions)) {
            $this->_result = $this->_result && ($this->_action == $actions);
        } else {
            foreach ($actions as $action) {
                if ($this->_action == $action) {
                    return $this;
                }
            }
            $this->_result = false;
        }

        return $this;
    }

    /**
     * Reset the result so that we can start a new query with the current route and request
     *
     * @return Query
     */
    public function reset()
    {
        $this->_result = true;

        return $this;
    }

    /**
     * Get the result as boolean
     *
     * @return bool
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Get the result class
     *
     * @param string $active
     * @param string $inactive
     *
     * @return string
     */
    public function getClass($active = 'active', $inactive = '')
    {
        return $this->_result ? $active : $inactive;
    }

}