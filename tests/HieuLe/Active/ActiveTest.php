<?php

class ActiveTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testUriMethod() {
        $request = Mockery::mock('\Illuminate\Http\Request');
        $request->shouldReceive('getPathInfo')->times(4)->andReturn('/');
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('getCurrentRequest')->times(4)->andReturn($request);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->uri('/'));
        $this->assertEquals('', $active->uri('/*'));
        $this->assertEquals('selected', $active->uri('/', 'selected'));
        $this->assertEquals('', $active->uri('/*', 'selected'));
    }

    public function testPatternMethod()
    {

        $request = Mockery::mock('\Illuminate\Http\Request');
        $request->shouldReceive('path')->times(4)->andReturn('foo/bar/baz');
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('getCurrentRequest')->times(4)->andReturn($request);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->pattern('foo/*'));
        $this->assertEquals('', $active->pattern('foo/'));
        $this->assertEquals('selected', $active->pattern('foo/*', 'selected'));
        $this->assertEquals('selected', $active->pattern(array('foo/*', '*bar/*'), 'selected'));
    }

    public function testRouteMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->times(5)->andReturn('foo');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->route('foo'));
        $this->assertEquals('selected', $active->route('foo', 'selected'));
        $this->assertEquals('active', $active->route(array('fooz', 'foo')));
        $this->assertEquals('', $active->route(array()));
        $this->assertEquals('', $active->route('bar'));
    }

    public function testRouteWithoutName()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->once()->andReturnNull();
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->route('foo'));
    }

    public function testActionMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->times(3)->andReturn('fooController@bar');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->action('fooController@bar'));
        $this->assertEquals('selected', $active->action(array('barController@baz', 'fooController@bar'), 'selected'));
        $this->assertEquals('', $active->action(array('barController@baz', 'fooController@baz'), 'selected'));

        $router->shouldReceive('currentRouteAction')->once()->andReturn(null);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->action(array('barController@baz', 'fooController@baz'), 'selected'));

        $router->shouldReceive('currentRouteAction')->times(4)->andReturn('App\\Http\\Controllers\\fooController@bar');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->action('fooController@bar'));
        $this->assertEquals('', $active->action('App\\Http\\Controllers\\fooController@bar'));
        $this->assertEquals('active', $active->action('App\\Http\\Controllers\\fooController@bar', 'active', true));
        $this->assertEquals('', $active->action('fooController@bar', 'active', true));
    }

    /**
     * @dataProvider providerForTestGetControllerMethod
     */
    public function testGetControllerMethod($controller, $result, $fullClassName = false)
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->once()->andReturn($controller);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals($result, $active->getController());
    }

    /**
     * @dataProvider providerForTestGetMethodName
     */
    public function testGetMethodName($method, $result)
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->once()->andReturn("Foo@{$method}");
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals($result, $active->getMethod());
    }

    public function testControllerMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->between(5, 10)->andReturn('FooBarController@getBaz');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->controller('Foo'));
        $this->assertEquals('active', $active->controller('FooBar'));
        $this->assertEquals('selected', $active->controller('FooBar', 'selected'));
        $this->assertEquals('selected', $active->controller('FooBar', 'selected', array('Foo')));
        $this->assertEquals('', $active->controller('FooBar', 'selected', array('Foo', 'Baz')));
    }

    public function testControllersMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->twice()->andReturn('FooBarController@getBaz');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->controllers(array('Foo', 'Bar', 'FooBar')));
        $this->assertEquals('', $active->controllers(array('Foo', 'Bar')));
    }
    
    public function testRoutePatternMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->times(4)->andReturn('prefix.foo.create');
        $active = new HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->routePattern('*.foo.*'));
        $this->assertEquals('', $active->routePattern('*.foo'));
        $this->assertEquals('selected', $active->routePattern('*.foo.*', 'selected'));
        $this->assertEquals('active', $active->routePattern('*.create'));
    }

    public function providerForTestGetControllerMethod()
    {
        return [
            ['FooController', 'Foo'],
            ['App\Http\Controllers\WelcomeController', 'App\Http\Controllers\Welcome'],
            ['SomethingControllerBazController', 'SomethingControllerBaz'],
            ['BazControllerFoo', 'BazControllerFoo'],
        ];
    }
    
    public function providerForTestGetMethodName()
    {
        return [
            ['showWelcome', 'Welcome'],
            ['getFoo', 'Foo'],
            ['postFoo', 'Foo'],
            ['deleteBar', 'Bar'],
            ['putBar', 'Bar'],
            ['postFooBaz', 'FooBaz'],
            ['deleteFooget', 'Fooget'],
            ['doShowpost', 'doShowpost'],
            ['show', 'show']
        ];
    }
    
}
