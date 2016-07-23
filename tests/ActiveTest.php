<?php

namespace HieuLe\ActiveTest;

use HieuLe\Active\Active;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class ActiveTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        app('router')->group(['middleware' => ['dump']], function () {
            app('router')->get('/foo/bar',
                ['as' => 'foo.bar', 'uses' => '\HieuLe\ActiveTest\Http\DumpController@indexMethod']);
            app('router')->get('/foo/bar/{id}/view',
                ['as' => 'foo.bar.view', 'uses' => '\HieuLe\ActiveTest\Http\DumpController@viewMethod']);
            app('router')->get('/home', [
                'as'   => 'home',
                'uses' => function () {
                },
            ]);
            app('router')->get('/', function () {
            });
            app('router')->bind('model', function ($id) {
                return new StubModel(['uid' => $id]);
            });
            app('router')->get('/model/{model}', '\HieuLe\ActiveTest\Http\DumpController@viewMethod');
        });
    }

    public function testReturnCorrectValueWhenNotInitiated()
    {
        $active = new Active(null);

        $this->assertFalse($active->checkAction([]));
        $this->assertFalse($active->checkRouteParam('', ''));
        $this->assertFalse($active->checkRoute([]));
        $this->assertFalse($active->checkRoutePattern([]));
        $this->assertFalse($active->checkUriPattern([]));
        $this->assertFalse($active->checkUri([]));
        $this->assertFalse($active->checkQuery('', ''));
        $this->assertFalse($active->checkController([]));
        $this->assertSame('', $active->getAction());
        $this->assertSame('', $active->getController());
        $this->assertSame('', $active->getMethod());
    }

    public function testGetCorrectClassWithCondition()
    {
        $active = new Active(null);

        $this->assertSame('active', $active->getClassIf(true));
        $this->assertSame('selected', $active->getClassIf(true, 'selected'));
        $this->assertSame('not-checked', $active->getClassIf(false, 'selected', 'not-checked'));
    }

    /**
     * @param Request $request
     * @param         $result
     *
     * @dataProvider provideGetActionTestData
     */
    public function testGetCorrectAction(Request $request, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::getAction());
        $this->assertSame($result, app('active')->getAction());
        $this->assertSame($result, current_action());
    }

    /**
     * @param Request $request
     * @param         $result
     *
     * @dataProvider provideGetMethodTestData
     */
    public function testGetCorrectMethod(Request $request, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::getMethod());
        $this->assertSame($result, app('active')->getMethod());
        $this->assertSame($result, current_method());
    }

    /**
     * @param Request $request
     * @param         $result
     *
     * @dataProvider provideGetControllerTestData
     */
    public function testGetCorrectController(Request $request, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::getController());
        $this->assertSame($result, app('active')->getController());
        $this->assertSame($result, current_controller());
    }

    /**
     * @param Request $request
     * @param         $actions
     * @param         $result
     *
     * @dataProvider provideCheckActionTestData
     */
    public function testCheckCurrentAction(Request $request, $actions, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkAction($actions));
        $this->assertSame($result, app('active')->checkAction($actions));
        $this->assertSame($result, if_action($actions));
    }

    /**
     * @param Request $request
     * @param
     *        $controllers
     * @param         $result
     *
     * @dataProvider provideCheckControllerTestData
     */
    public function testCheckCurrentController(Request $request, $controllers, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkController($controllers));
        $this->assertSame($result, app('active')->checkController($controllers));
        $this->assertSame($result, if_controller($controllers));
    }

    /**
     * @param Request $request
     * @param         $routes
     * @param         $result
     *
     * @dataProvider provideCheckRouteTestData
     */
    public function testCheckCurrentRoute(Request $request, $routes, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkRoute($routes));
        $this->assertSame($result, app('active')->checkRoute($routes));
        $this->assertSame($result, if_route($routes));
    }

    /**
     * @param Request $request
     * @param         $routes
     * @param         $result
     *
     * @dataProvider provideCheckRoutePatternTestData
     */
    public function testCheckCurrentRoutePattern(Request $request, $routes, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkRoutePattern($routes));
        $this->assertSame($result, app('active')->checkRoutePattern($routes));
        $this->assertSame($result, if_route_pattern($routes));
    }

    /**
     * @param Request $request
     * @param         $key
     * @param         $value
     * @param         $result
     *
     * @dataProvider provideCheckRouteParameterTestData
     */
    public function testCheckCurrentRouteParameter(Request $request, $key, $value, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkRouteParam($key, $value));
        $this->assertSame($result, app('active')->checkRouteParam($key, $value));
        $this->assertSame($result, if_route_param($key, $value));
    }

    /**
     * @param Request      $request
     * @param array|string $uri
     * @param              $result
     *
     * @dataProvider provideCheckUriTestData
     */
    public function testCheckCurrentUri(Request $request, $uri, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkUri($uri));
        $this->assertSame($result, app('active')->checkUri($uri));
        $this->assertSame($result, if_uri($uri));
    }

    /**
     * @param Request $request
     * @param         $uri
     * @param         $result
     *
     * @dataProvider provideCheckUriPatternTestData
     */
    public function testCheckCurrentUriPattern(Request $request, $uri, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkUriPattern($uri));
        $this->assertSame($result, app('active')->checkUriPattern($uri));
        $this->assertSame($result, if_uri_pattern($uri));
    }

    /**
     * @param Request $request
     * @param         $key
     * @param         $value
     * @param         $result
     *
     * @dataProvider provideCheckQueryTestData
     */
    public function testCheckCurrentQuerystring(Request $request, $key, $value, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkQuery($key, $value));
        $this->assertSame($result, app('active')->checkQuery($key, $value));
        $this->assertSame($result, if_query($key, $value));
    }

    public function testAliasAndHelperFunctions()
    {
        $this->assertSame('active', \Active::getClassIf(true));
        $this->assertSame('active', active_class(true));
    }

    //<editor-fold desc="Data providers">
    public function provideGetActionTestData()
    {
        return [
            'action is a controller method' => [
                Request::create('/foo/bar'),
                '\HieuLe\ActiveTest\Http\DumpController@indexMethod',
            ],
            'action is a closure'           => [
                Request::create('/home'),
                'Closure',
            ],
        ];
    }

    public function provideGetMethodTestData()
    {
        return [
            'method is a controller method'                          => [
                Request::create('/foo/bar'),
                'indexMethod',
            ],
            'method is a controller method and the route has params' => [
                Request::create('/foo/bar/1/view'),
                'viewMethod',
            ],
            'method is a closure'                                    => [
                Request::create('/home'),
                '',
            ],
        ];
    }

    public function provideGetControllerTestData()
    {
        return [
            'controller is a controller method' => [
                Request::create('/foo/bar'),
                '\HieuLe\ActiveTest\Http\DumpController',
            ],
            'controller is a closure'           => [
                Request::create('/home'),
                'Closure',
            ],
        ];
    }

    public function provideCheckActionTestData()
    {
        return [
            'match the first inputted actions'  => [
                Request::create('/foo/bar'),
                '\HieuLe\ActiveTest\Http\DumpController@indexMethod',
                true,
            ],
            'match the second inputted actions' => [
                Request::create('/foo/bar'),
                [
                    '\HieuLe\ActiveTest\Http\DumpController@viewMethod',
                    '\HieuLe\ActiveTest\Http\DumpController@indexMethod',
                ],
                true,
            ],
            'match no action'                   => [
                Request::create('/foo/bar'),
                [
                    '\HieuLe\ActiveTest\Http\DumpController@viewMethod',
                    '\HieuLe\ActiveTest\Http\DumpController@deleteMethod',
                ],
                false,
            ],
        ];
    }

    public function provideCheckControllerTestData()
    {
        return [
            'match the first inputted controllers'  => [
                Request::create('/foo/bar'),
                '\HieuLe\ActiveTest\Http\DumpController',
                true,
            ],
            'match the second inputted controllers' => [
                Request::create('/foo/bar'),
                ['Namespace\Child\Controller', '\HieuLe\ActiveTest\Http\DumpController'],
                true,
            ],
            'match no controller'                   => [
                Request::create('/foo/bar'),
                ['Controller', 'Namespace\Child\Controller'],
                false,
            ],
        ];
    }

    public function provideCheckRouteTestData()
    {
        return [
            'match the first inputted route names'  => [
                Request::create('/foo/bar'),
                'foo.bar',
                true,
            ],
            'match the second inputted route names' => [
                Request::create('/foo/bar'),
                ['foo.bar.view', 'foo.bar'],
                true,
            ],
            'match no route name'                   => [
                Request::create('/foo/bar'),
                ['foo.bar.view', 'foo.bar.delete'],
                false,
            ],
            'route with no name'                    => [
                Request::create('/'),
                ['foo.bar.view', null],
                true,
            ],
        ];
    }

    public function provideCheckRouteParameterTestData()
    {
        return [
            'key value is matched'     => [
                Request::create('/foo/bar/1/view'),
                'id',
                '1',
                true,
            ],
            'key does not exist'       => [
                Request::create('/foo/bar/1/view'),
                'foo',
                '1',
                false,
            ],
            'key value is not matched' => [
                Request::create('/foo/bar/1/view'),
                'id',
                '2',
                false,
            ],
            'match a route bound to a model' => [
                Request::create('/model/100'),
                'model',
                '100',
                true,
            ],
            'not match a route bound to another model' => [
                Request::create('/model/100'),
                'model',
                '1',
                false,
            ],
        ];
    }

    public function provideCheckRoutePatternTestData()
    {
        return [
            'match the first inputted route patterns'  => [
                Request::create('/foo/bar'),
                'foo.*',
                true,
            ],
            'match the second inputted route patterns' => [
                Request::create('/foo/bar'),
                ['bar.*', 'foo.*'],
                true,
            ],
            'match no route pattern'                   => [
                Request::create('/foo/bar'),
                ['bar.*', 'baz.*'],
                false,
            ],
            'route with no name'                       => [
                Request::create('/'),
                ['foo.*', null],
                true,
            ],
        ];
    }

    public function provideCheckUriTestData()
    {
        return [
            'match the first inputted uri'  => [
                Request::create('/foo/bar'),
                'foo/bar',
                true,
            ],
            'match the second inputted uri' => [
                Request::create('/foo/bar'),
                ['/foo/bar/view', 'foo/bar'],
                true,
            ],
            'match no uri'                  => [
                Request::create('/foo/bar'),
                ['/foo/bar', '/foo/bar/delete'],
                false,
            ],
            'root route'                    => [
                Request::create('/'),
                ['/'],
                true,
            ],
        ];
    }

    public function provideCheckQueryTestData()
    {
        return [
            'key value is matched'                                      => [
                Request::create('/foo/bar', 'GET', ['id' => 1]),
                'id',
                '1',
                true,
            ],
            'key exists'                                                => [
                Request::create('/foo/bar', 'GET', ['id' => 1]),
                'id',
                false,
                true,
            ],
            'key does not exist'                                        => [
                Request::create('/foo/bar'),
                'foo',
                '1',
                false,
            ],
            'key value is not matched'                                  => [
                Request::create('/foo/bar', 'GET', ['id' => 1]),
                'id',
                '2',
                false,
            ],
            'key is an array that contains the input with wrong type'   => [
                Request::create('/foo/bar', 'GET', ['id' => [1, 2]]),
                'id',
                '2',
                true,
            ],
            'key is an array that contains the input with correct type' => [
                Request::create('/foo/bar', 'GET', ['id' => [1, 2]]),
                'id',
                2,
                true,
            ],
            'key is an array that does not contain the input'           => [
                Request::create('/foo/bar', 'GET', ['id' => [1, 2]]),
                'id',
                '3',
                false,
            ],
        ];
    }

    public function provideCheckUriPatternTestData()
    {
        return [
            'match the first inputted uri patterns'  => [
                Request::create('/foo/bar'),
                'foo/*',
                true,
            ],
            'match the second inputted uri patterns' => [
                Request::create('/foo/bar'),
                ['bar/*', 'foo/*'],
                true,
            ],
            'match no uri pattern'                   => [
                Request::create('/foo/bar'),
                ['bar/*', 'baz/*'],
                false,
            ],
        ];
    }

    //</editor-fold>

    protected function getPackageProviders($app)
    {
        return [
            \HieuLe\Active\ActiveServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Active' => \HieuLe\Active\Facades\Active::class,
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Http\Kernel::class);
    }
}
