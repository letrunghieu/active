<?php

namespace HieuLe\ActiveTest\Http;

class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    protected $routeMiddleware = [
        'dump' => DumpMiddleware::class,
    ];
}