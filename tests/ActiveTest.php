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
            app('router')->get('/foo/bar', ['as' => 'foo.bar', 'uses' => 'Namespace\Controller@indexMethod']);
            app('router')->get('/foo/bar/{id}/view',
                ['as' => 'foo.bar.view', 'uses' => 'Namespace\Controller@viewMethod']);
            app('router')->get('/home', [
                'as'   => 'home',
                'uses' => function () {
                },
            ]);
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
     * @param array   $actions
     * @param         $result
     *
     * @dataProvider provideCheckActionTestData
     */
    public function testCheckCurrentAction(Request $request, array $actions, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkAction($actions));
        $this->assertSame($result, app('active')->checkAction($actions));
        $this->assertSame($result, if_action($actions));
    }

    /**
     * @param Request $request
     * @param array   $controllers
     * @param         $result
     *
     * @dataProvider provideCheckControllerTestData
     */
    public function testCheckCurrentController(Request $request, array $controllers, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkController($controllers));
        $this->assertSame($result, app('active')->checkController($controllers));
        $this->assertSame($result, if_controller($controllers));
    }

    /**
     * @param Request $request
     * @param array   $routes
     * @param         $result
     *
     * @dataProvider provideCheckRouteTestData
     */
    public function testCheckCurrentRoute(Request $request, array $routes, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkRoute($routes));
        $this->assertSame($result, app('active')->checkRoute($routes));
        $this->assertSame($result, if_route($routes));
    }

    /**
     * @param Request $request
     * @param array   $routes
     * @param         $result
     *
     * @dataProvider provideCheckRoutePatternTestData
     */
    public function testCheckCurrentRoutePattern(Request $request, array $routes, $result)
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
     * @param Request $request
     * @param array   $uri
     * @param         $result
     *
     * @dataProvider provideCheckUriTestData
     */
    public function testCheckCurrentUri(Request $request, array $uri, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkUri($uri));
        $this->assertSame($result, app('active')->checkUri($uri));
        $this->assertSame($result, if_uri($uri));
    }

    /**
     * @param Request $request
     * @param array   $uri
     * @param         $result
     *
     * @dataProvider provideCheckUriPatternTestData
     */
    public function testCheckCurrentUriPattern(Request $request, array $uri, $result)
    {
        app(HttpKernelContract::class)->handle($request);

        $this->assertSame($result, \Active::checkUriPattern($uri));
        $this->assertSame($result, app('active')->checkUriPattern($uri));
        $this->assertSame($result, if_uri_patterns($uri));
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
                'Namespace\Controller@indexMethod',
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
            'action is a controller method'                          => [
                Request::create('/foo/bar'),
                'indexMethod',
            ],
            'action is a controller method and the route has params' => [
                Request::create('/foo/bar/1/view'),
                'viewMethod',
            ],
            'action is a closure'                                    => [
                Request::create('/home'),
                '',
            ],
        ];
    }

    public function provideGetControllerTestData()
    {
        return [
            'action is a controller method' => [
                Request::create('/foo/bar'),
                'Namespace\Controller',
            ],
            'action is a closure'           => [
                Request::create('/home'),
                'Closure',
            ],
        ];
    }

    public function provideCheckActionTestData()
    {
        return [
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['Namespace\Controller@indexMethod'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['Namespace\Controller@viewMethod', 'Namespace\Controller@indexMethod'],
                true,
            ],
            'match nothing'                  => [
                Request::create('/foo/bar'),
                ['Namespace\Controller@viewMethod', 'Namespace\Controller@deleteMethod'],
                false,
            ],
        ];
    }

    public function provideCheckControllerTestData()
    {
        return [
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['Namespace\Controller'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['Namespace\Child\Controller', 'Namespace\Controller'],
                true,
            ],
            'match nothing'                  => [
                Request::create('/foo/bar'),
                ['Controller', 'Namespace\Child\Controller'],
                false,
            ],
        ];
    }

    public function provideCheckRouteTestData()
    {
        return [
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['foo.bar'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['foo.bar.view', 'foo.bar'],
                true,
            ],
            'match nothing'                  => [
                Request::create('/foo/bar'),
                ['foo.bar.view', 'foo.bar.delete'],
                false,
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
        ];
    }

    public function provideCheckRoutePatternTestData()
    {
        return [
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['foo.*'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['bar.*', 'foo.*'],
                true,
            ],
            'match nothing'                  => [
                Request::create('/foo/bar'),
                ['bar.*', 'baz.*'],
                false,
            ],
        ];
    }

    public function provideCheckUriTestData()
    {
        return [
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['foo/bar'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['/foo/bar/view', 'foo/bar'],
                true,
            ],
            'match nothing'                  => [
                Request::create('/foo/bar'),
                ['/foo/bar', '/foo/bar/delete'],
                false,
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
            'match the first inputted item'  => [
                Request::create('/foo/bar'),
                ['foo/*'],
                true,
            ],
            'match the second inputted item' => [
                Request::create('/foo/bar'),
                ['bar/*', 'foo/*'],
                true,
            ],
            'match nothing'                  => [
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
