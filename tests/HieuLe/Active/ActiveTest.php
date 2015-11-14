<?php
use HieuLe\Active\Active;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class ActiveTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        app('router')->get('/foo/bar', ['as' => 'foo.bar', 'Namespace\Controller@indexMethod']);
        app('router')->get('/foo/bar/{id}/view', ['as' => 'foo.bar.view', 'Namespace\Controller@viewMethod']);
        app('router')->get('/home', [
            'as'   => 'home',
            'uses' => function () {
            },
        ]);
    }

    public function testReturnCorrectValueWhenNotInitiated()
    {
        $active = new Active(null);

        $this->assertFalse($active->checkAction([]));
        $this->assertFalse($active->checkRouteParameter('', ''));
        $this->assertFalse($active->checkRoute([]));
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

//    public function testGetCorrectAction(Request $request)
//    {
//
//    }

    public function testAliasAndHelperFunctions() {
        $this->assertSame('active', \Active::getClassIf(true));
        $this->assertSame('active', active_class(true));
    }

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
}
