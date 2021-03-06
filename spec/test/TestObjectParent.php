<?php

declare(strict_types=1);

namespace drupol\DynamicObjects\test;

use drupol\DynamicObjects\DynamicObject;

class TestObjectParent extends DynamicObject
{
    public $property = 'hello';

    public function customMethod()
    {
        return 'hello world';
    }
}
