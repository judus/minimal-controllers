<?php

namespace Maduser\Minimal\Controllers\Tests;

use Maduser\Minimal\Controllers\Factories\ControllerFactory;
use PHPUnit\Framework\TestCase;

class ControllerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $cf = new ControllerFactory();

        $params = [123,456];
        $class = DummyClassB::class;

        $result = $cf->create($params, $class);

        $this->assertEquals(new DummyClassB(123,456), $result);
        $this->assertEquals('123', $result->a);
        $this->assertEquals('456', $result->b);
    }
}

class DummyClassB
{
    public $a;

    public $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}