<?php

class ActiveTest extends PHPUnit_Framework_TestCase
{

    public function testPatternMethod()
    {
	$route = Mockery::mock('\Illuminate\Routing\Route');
	$route->shouldReceive('getUri')->once()->andReturn('foo/bar/baz');
	$active = new \HieuLe\Active\Active($route);
	$this->assertEquals('active', $active->pattern('foo/*'));
	$this->assertEquals('', $active->pattern('foo/'));
	$this->assertEquals('selected', $active->pattern('foo/*', 'selected'));
	$this->assertEquals('selected', $active->pattern(array('foo/*', '*bar/*'), 'selected'));
    }

}