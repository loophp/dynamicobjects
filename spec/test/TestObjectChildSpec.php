<?php

namespace spec\drupol\DynamicObjects\test;

use drupol\DynamicObjects\test\TestObjectChild;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TestObjectChildSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TestObjectChild::class);
    }

    public function it_can_call_the_parent_to_get_a_property()
    {
        $this->property->shouldBe('hello');
    }

    public function it_can_call_the_parent_to_get_a_method()
    {
        $this->customMethod()->shouldBe('hello world');
    }
}
