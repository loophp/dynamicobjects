<?php

namespace spec\drupol\DynamicObjects;

use drupol\DynamicObjects\DynamicObject;
use drupol\DynamicObjects\test\ExampleClass;
use PhpSpec\ObjectBehavior;

class DynamicObjectSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DynamicObject::class);
    }

    public function it_can_create_dynamic_property() {
        $this::addDynamicProperty('hello', 'world');
        $this::hasDynamicProperty('hello')->shouldBe(TRUE);
        $this->hello->shouldBe('world');
        $this->hello = 'foo';
        $this->hello->shouldBe('foo');
    }

    public function it_can_detect_if_a_dynamic_property_has_been_added() {
        $this->fake = 'property';
        $this::hasDynamicProperty('fake')->shouldBe(FALSE);
    }

    public function it_can_return_appropriate_values_for_an_undefined_property() {
        $this::getDynamicProperty('undefinedProperty')->shouldBeNull();
        $this->shouldThrow(new \DomainException(sprintf('Undefined property: %s.', 'undefinedProperty')))->during('__get', ['undefinedProperty']);
    }

    public function it_can_clear_dynamic_properties() {
        $this::addDynamicProperty('hello', 'world');
        $this::hasDynamicProperty('hello')->shouldBe(TRUE);
        $this::clearDynamicProperties();
        $this::hasDynamicProperty('hello')->shouldBe(FALSE);
    }

    public function it_can_remove_a_dynamic_property() {
        $this::addDynamicProperty('hello', 'world');
        $this::removeDynamicProperty('hello');
        $this::hasDynamicProperty('hello')->shouldBe(FALSE);
    }

    public function it_can_create_dynamic_method() {
        $this::addDynamicMethod('hello', function() {return 'world';});
        $this::hasDynamicMethod('hello')->shouldBe(TRUE);
        $this->hello()->shouldBe('world');
    }

    public function it_can_detect_if_a_dynamic_method_has_been_added() {
        $this->fake = function() {return 'method';};
        $this::hasDynamicMethod('fake')->shouldBe(FALSE);
    }

    public function it_can_return_appropriate_values_for_a_method() {
        $this->shouldThrow(new \BadMethodCallException(sprintf('Undefined method: %s().', 'undefinedMethod')))->during('undefinedMethod');
    }

    public function it_can_clear_dynamic_methods() {
        $this::addDynamicMethod('hello', function() {return 'world';});
        $this::hasDynamicMethod('hello')->shouldBe(TRUE);
        $this::clearDynamicMethods();
        $this::hasDynamicMethod('hello')->shouldBe(FALSE);
    }

    public function it_can_remove_a_dynamic_method() {
        $this::addDynamicMethod('hello', function() {return 'world';});
        $this::removeDynamicMethod('hello');
        $this::hasDynamicMethod('hello')->shouldBe(FALSE);
    }

    public function it_can_create_method_in_property() {
        $this::addDynamicProperty('hello', function() {return 'world';});
        $this->hello->shouldBe('world');
    }

    public function it_can_memoize_dynamic_property() {
        $this::addDynamicProperty('hello', function() {sleep(1); return microtime();}, true);
        $this->hello->shouldBe($this->hello);

        $this::addDynamicProperty('hello', function() {sleep(1); return microtime();}, false);
        $this->hello->shouldNotBe($this->hello);
    }

    public function it_can_memoize_dynamic_method() {
        $this::addDynamicMethod('hello', function() {sleep(1); return microtime();}, true);
        $this->hello()->shouldBe($this->hello());

        $this::addDynamicMethod('hello', function() {sleep(1); return microtime();}, false);
        $this->hello()->shouldNotBe($this->hello());
    }

    public function it_can_convert_a_class_into_an_anonymous_class() {
        $class = new ExampleClass();
        $this::convertToAnonymous($class)->renderProperties()->shouldBe($class->renderProperties());
        $this::convertToAnonymous($class)->renderMethods()->shouldBe($class->renderMethods());
    }
}
