<?php

class ActiveTest extends PHPUnit_Framework_TestCase
{

    public function testPatternMethod()
    {

	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getUri')->once()->andReturn('foo/bar/baz');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('active', $active->pattern('foo/*'));
	$this->assertEquals('', $active->pattern('foo/'));
	$this->assertEquals('selected', $active->pattern('foo/*', 'selected'));
	$this->assertEquals('selected', $active->pattern(array('foo/*', '*bar/*'), 'selected'));
    }

    public function testRouteMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getName')->once()->andReturn('foo');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('active', $active->route('foo'));
	$this->assertEquals('selected', $active->route('foo', 'selected'));
	$this->assertEquals('active', $active->route(array('fooz', 'foo')));
	$this->assertEquals('', $active->route(array()));
	$this->assertEquals('', $active->route('bar'));
    }

    public function testRouteWithoutName()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getName')->once()->andReturnNull();
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('', $active->route('foo'));
    }

    public function testActionMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getActionName')->once()->andReturn('fooController@bar');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('active', $active->action('fooController@bar'));
	$this->assertEquals('selected', $active->action(array('barController@baz', 'fooController@bar'), 'selected'));
	$this->assertEquals('', $active->action(array('barController@baz', 'fooController@baz'), 'selected'));
    }

    public function testGetControllerMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getActionName')->once()->andReturn('FooBarController@bar');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('FooBar', $active->getController());
    }

    public function testGetMethodName()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getActionName')->once()->andReturn('FooBarController@getBaz');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('Baz', $active->getMethod());
    }

    public function testControllerMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getActionName')->once()->andReturn('FooBarController@getBaz');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('', $active->controller('Foo'));
	$this->assertEquals('active', $active->controller('FooBar'));
	$this->assertEquals('selected', $active->controller('FooBar', 'selected'));
	$this->assertEquals('selected', $active->controller('FooBar', 'selected', array('Foo')));
	$this->assertEquals('', $active->controller('FooBar', 'selected', array('Foo', 'Baz')));
    }

    public function testControllersMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getActionName')->once()->andReturn('FooBarController@getBaz');
	$router = Mockery::mock('\Illuminate\Routing\Router');
	$router->shouldReceive('current')->once()->andReturn($route);
	$active = new \HieuLe\Active\Active($router);
	$this->assertEquals('active', $active->controllers(array('Foo', 'Bar', 'FooBar')));
	$this->assertEquals('', $active->controllers(array('Foo', 'Bar')));
    }

}