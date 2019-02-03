<?php

declare(strict_types = 1);

namespace spec\drupol\DynamicObjects\test;

use drupol\DynamicObjects\test\TestObjectParent;
use PhpSpec\ObjectBehavior;

class TestObjectParentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TestObjectParent::class);
    }
}
