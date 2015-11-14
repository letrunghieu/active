<?php

class ActiveTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testUriMethod()
    {
        $request = Mockery::mock('\Illuminate\Http\Request');
        $request->shouldReceive('getPathInfo')->times(4)->andReturn('/');
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('getCurrentRequest')->times(4)->andReturn($request);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->uri('/'));
        $this->assertEquals('', $active->uri('/*'));
        $this->assertEquals('selected', $active->uri('/', 'selected'));
        $this->assertEquals('normal', $active->uri('/*', 'selected', 'normal'));
    }

    public function testPatternMethod()
    {

        $request = Mockery::mock('\Illuminate\Http\Request');
        $request->shouldReceive('path')->times(5)->andReturn('foo/bar/baz');
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('getCurrentRequest')->times(5)->andReturn($request);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->pattern('foo/*'));
        $this->assertEquals('', $active->pattern('foo/'));
        $this->assertEquals('normal', $active->pattern('foo/', 'active', 'normal'));
        $this->assertEquals('selected', $active->pattern('foo/*', 'selected'));
        $this->assertEquals('selected', $active->pattern(['foo/*', '*bar/*'], 'selected'));
    }

    public function testRouteMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->times(6)->andReturn('foo');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->route('foo'));
        $this->assertEquals('selected', $active->route('foo', 'selected'));
        $this->assertEquals('active', $active->route(['fooz', 'foo']));
        $this->assertEquals('', $active->route([]));
        $this->assertEquals('', $active->route('bar'));
        $this->assertEquals('normal', $active->route('bar', 'active', 'normal'));
    }

    public function testRouteWithoutName()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->times(2)->andReturnNull();
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->route('foo'));
        $this->assertEquals('normal', $active->route('foo', 'active', 'normal'));
    }

    public function testActionMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->times(4)->andReturn('fooController@bar');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->action('fooController@bar'));
        $this->assertEquals('selected', $active->action(['barController@baz', 'fooController@bar'], 'selected'));
        $this->assertEquals('', $active->action(['barController@baz', 'fooController@baz'], 'selected'));
        $this->assertEquals('normal',
            $active->action(['barController@baz', 'fooController@baz'], 'selected', false, 'normal'));

        $router->shouldReceive('currentRouteAction')->times(2)->andReturn(null);
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->action(['barController@baz', 'fooController@baz'], 'selected'));
        $this->assertEquals('normal',
            $active->action(['barController@baz', 'fooController@baz'], 'selected', false, 'normal'));

        $router->shouldReceive('currentRouteAction')->times(4)->andReturn('App\\Http\\Controllers\\fooController@bar');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->action('fooController@bar'));
        $this->assertEquals('', $active->action('App\\Http\\Controllers\\fooController@bar'));
        $this->assertEquals('active', $active->action('App\\Http\\Controllers\\fooController@bar', 'active', true));
        $this->assertEquals('normal', $active->action('fooController@bar', 'active', true, 'normal'));
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
        $router->shouldReceive('currentRouteAction')->between(6, 12)->andReturn('FooBarController@getBaz');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('', $active->controller('Foo'));
        $this->assertEquals('active', $active->controller('FooBar'));
        $this->assertEquals('selected', $active->controller('FooBar', 'selected'));
        $this->assertEquals('selected', $active->controller('FooBar', 'selected', ['Foo']));
        $this->assertEquals('', $active->controller('FooBar', 'selected', ['Foo', 'Baz']));
        $this->assertEquals('normal', $active->controller('FooBar', 'selected', ['Foo', 'Baz'], 'normal'));
    }

    public function testControllersMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteAction')->times(3)->andReturn('FooBarController@getBaz');
        $active = new \HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->controllers(['Foo', 'Bar', 'FooBar']));
        $this->assertEquals('', $active->controllers(['Foo', 'Bar']));
        $this->assertEquals('normal', $active->controllers(['Foo', 'Bar'], 'selected', 'normal'));
    }

    public function testRoutePatternMethod()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('currentRouteName')->times(5)->andReturn('prefix.foo.create');
        $active = new HieuLe\Active\Active($router);
        $this->assertEquals('active', $active->routePattern('*.foo.*'));
        $this->assertEquals('', $active->routePattern('*.foo'));
        $this->assertEquals('normal', $active->routePattern('*.foo', 'active', 'normal'));
        $this->assertEquals('selected', $active->routePattern('*.foo.*', 'selected'));
        $this->assertEquals('active', $active->routePattern('*.create'));
    }

    public function testQueryMethod()
    {
        $request = Mockery::mock('\Illuminate\Http\Request');
        $request->shouldReceive('query')->times(6)->andReturnUsing(function ($arg) {
            switch ($arg) {
                case 'foo':
                    return 'bar';
                case 'lorems':
                    return ['baz', 'ipsum'];
            }
            return null;
        });
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('getCurrentRequest')->times(6)->andReturn($request);
        $active = new \HieuLe\Active\Active($router);

        $this->assertEquals('active', $active->query('foo', 'bar'));
        $this->assertEquals('', $active->query('foo', 'barr'));
        $this->assertEquals('normal', $active->query('foo', 'barr', 'active', 'normal'));
        $this->assertEquals('selected', $active->query('lorems', 'baz', 'selected'));
        $this->assertEquals('', $active->query('lorems', 'bazz', 'selected'));
        $this->assertEquals('normal', $active->query('lorems', 'bazz', 'selected', 'normal'));
    }

    public function testRouteParameter()
    {
        $route = Mockery::mock(\Illuminate\Routing\Route::class);
        $route->shouldReceive('getName')->andReturn('foo');
        $route->shouldReceive('parameter')->andReturnUsing(function ($name) {
            switch ($name) {
                case 'id':
                    return 1;
                case 'bar':
                    return 'lorem';
                default:
                    return null;
            }
        });

        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('current')->andReturn($route);

        $active = new HieuLe\Active\Active($router);

        $this->assertSame('', $active->routeParam('bar', []));
        $this->assertSame('active', $active->routeParam('foo', []));
        $this->assertSame('active', $active->routeParam('foo', [
            'id' => 1,
        ]));
        $this->assertSame('active', $active->routeParam('foo', [
            'id' => 1,
            'bar' => 'lorem',
        ]));
        $this->assertSame('', $active->routeParam('foo', [
            'id' => 1,
            'bar' => 'lorem',
            'baz' => 'ipsum'
        ]));
        $this->assertSame('', $active->routeParam('foo', [
            'id' => 2,
            'bar' => 'lorem',
        ]));
    }

    public function testRouteParameterWithEmptyRoute()
    {
        $router = Mockery::mock('\Illuminate\Routing\Router');
        $router->shouldReceive('current')->andReturn(null);

        $active = new HieuLe\Active\Active($router);

        $this->assertSame('', $active->routeParam('foo', []));
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
            ['show', 'show'],
        ];
    }

}
