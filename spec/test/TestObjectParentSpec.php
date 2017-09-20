<?php

namespace spec\drupol\DynamicObjects\test;

use drupol\DynamicObjects\test\TestObjectParent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TestObjectParentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TestObjectParent::class);
    }
}
