<?php

declare(strict_types = 1);

namespace spec\drupol\DynamicObjects;

use drupol\DynamicObjects\DynamicObject;
use PhpSpec\ObjectBehavior;

class DynamicObjectSpec extends ObjectBehavior
{
    public function it_can_add_a_callable()
    {
        $this
            ->addDynamicMethod('alias', 'strtoupper');

        $this
            ->alias('hello')
            ->shouldReturn('HELLO');
    }

    public function it_can_add_dynamic_method()
    {
        $closure = static function () {
            return 'world';
        };
        $this::addDynamicMethod('hello', $closure);
        $this::hasDynamicMethod('hello')->shouldBe(true);
        $this->getDynamicMethod('hello')->shouldReturn([
            'name' => 'hello',
            'factory' => $closure,
            'static' => false,
        ]);
        $this->__call('hello')->shouldBe($this->hello());

        $this::addDynamicMethod('goodbye', static function () {
            return 'cruel world';
        }, true);

        $this::hasDynamicMethod('goodbye')->shouldReturn(true);
        $this->__callStatic('goodbye')->shouldBe($this::goodbye());

        $this::addDynamicMethod(['goodafteroon', 'goodbye'], static function () {
            return __FUNCTION__;
        });
        $this::hasDynamicMethod('goodafteroon')->shouldBe(true);
        $this::hasDynamicMethod('goodbye')->shouldBe(true);
        $this->goodbye()->shouldBe($this->goodafteroon());

        $this::addDynamicMethod('random', static function () {
            return uniqid();
        });
        $this->random()->shouldNotBe($this->random());

        $this::addDynamicMethod('random', static function () {
            return true;
        }, true);
        $this->random()->shouldReturn(true);

        $this::addDynamicMethod('teststaticdefault', static function () {
            return 'teststatic';
        }, false);
        $this->teststaticdefault()->shouldReturn('teststatic');
        $this->shouldThrow('\Exception')->during('__callStatic', ['teststaticdefault']);

        $this::addDynamicMethod('teststaticfalse', static function () {
            return 'teststatic';
        }, false);
        $this->teststaticfalse()->shouldReturn('teststatic');
        $this->shouldThrow('\Exception')->during('__callStatic', ['teststaticfalse']);

        $this::addDynamicMethod('teststatictrue', static function () {
            return 'teststatic';
        }, true);
        $this->teststatictrue()->shouldReturn('teststatic');
        $this->shouldNotThrow('\Exception')->during('__callStatic', ['teststatictrue']);
    }

    public function it_can_add_dynamic_property()
    {
        $this::addDynamicProperty('hello', 'world');
        $this::hasDynamicProperty('hello')->shouldBe(true);
        $this->hello->shouldBe('world');
        $this->hello = 'foo';
        $this->hello->shouldBe('foo');

        $this::addDynamicProperty(['goodafteroon', 'goodbye'], 'world');
        $this::hasDynamicProperty('goodafteroon')->shouldBe(true);
        $this::hasDynamicProperty('goodbye')->shouldBe(true);
        $this->goodbye->shouldBe($this->goodafteroon);

        $this::addDynamicProperty('memoize', 'setToFalse');
        $this->getDynamicProperty('memoize')->shouldReturn(
            [
                'name' => 'memoize',
                'factory' => 'setToFalse',
            ]
        );

        $this::addDynamicProperty('memoize', 'setToTrue', true);
        $this->getDynamicProperty('memoize')->shouldReturn(
            [
                'name' => 'memoize',
                'factory' => 'setToTrue',
            ]
        );
    }

    public function it_can_be_extended()
    {
        $extensions = static function ($object) {
            $object::addDynamicMethod('foo', static function () {
                return 'bar';
            });
            $object::addDynamicMethod('bar', static function () {
                return 'foo';
            });
        };

        $this
            ->extend($extensions)
            ->shouldReturn($this);

        $this->foo()->shouldBe('bar');
        $this->bar()->shouldBe('foo');

        $this->extend('./spec/fixtures/extensions.php');

        $this->barfoo()->shouldBe('foobar');
        $this->barbaz()->shouldBe('bazbar');

        $this->shouldThrow(new \InvalidArgumentException(
            'DynamicObjectsTrait::extend() requires a callable or a file that returns one.'
        ))->during('extend', ['./spec/fixtures/unexistent.php']);
    }

    public function it_can_clear_dynamic_methods()
    {
        $this::addDynamicMethod('hello', static function () {
            return 'world';
        });
        $this::hasDynamicMethod('hello')->shouldBe(true);
        $this::clearDynamicMethods();
        $this::hasDynamicMethod('hello')->shouldBe(false);
    }

    public function it_can_clear_dynamic_properties()
    {
        $this::addDynamicProperty('hello', 'world');
        $this::hasDynamicProperty('hello')->shouldBe(true);
        $this::clearDynamicProperties();
        $this::hasDynamicProperty('hello')->shouldBe(false);
    }

    public function it_can_create_method_in_property()
    {
        $this::addDynamicProperty('hello', static function () {
            return 'world';
        });
        $this->hello->shouldBe('world');
    }

    public function it_can_detect_if_a_dynamic_method_has_been_added()
    {
        $this->fake = static function () {
            return 'method';
        };
        $this::hasDynamicMethod('fake')->shouldBe(false);
    }

    public function it_can_detect_if_a_dynamic_property_has_been_added()
    {
        $this->fake = 'property';
        $this::hasDynamicProperty('fake')->shouldBe(false);
    }

    public function it_can_do_dynamic_request()
    {
        $closure = static function ($input) {
            return $input;
        };
        $this::addDynamicMethod('echo', $closure);
        $this->doDynamicRequest($closure, ['oh oh oh'])->shouldReturn('oh oh oh');

        $closure = static function ($input) {
            return $input . uniqid();
        };
        $this::addDynamicMethod('echo', $closure);
        $this->doDynamicRequest($closure, ['oh oh oh'])->shouldNotBe($this->doDynamicRequest($closure, ['oh oh oh']));
    }

    public function it_can_remove_a_dynamic_method()
    {
        $this::addDynamicMethod('hello', static function () {
            return 'world';
        });
        $this::removeDynamicMethod('hello');
        $this::hasDynamicMethod('hello')->shouldBe(false);
    }

    public function it_can_remove_a_dynamic_property()
    {
        $this::addDynamicProperty('hello', 'world');
        $this::removeDynamicProperty('hello');
        $this::hasDynamicProperty('hello')->shouldBe(false);
    }

    public function it_can_return_appropriate_values_for_a_method()
    {
        $this->shouldThrow(new \BadMethodCallException(sprintf('Undefined method: %s().', 'undefinedMethod')))->during('undefinedMethod');
        $this->shouldThrow(new \BadMethodCallException(sprintf('Undefined static method: %s().', 'undefinedMethod')))->during('__callStatic', ['undefinedMethod']);
    }

    public function it_can_return_appropriate_values_for_an_undefined_property()
    {
        $this::getDynamicProperty('undefinedProperty')->shouldBeNull();
        $this->shouldThrow(new \DomainException(sprintf('Undefined property: %s.', 'undefinedProperty')))->during('__get', ['undefinedProperty']);
    }

    public function it_can_work_with_anonymous_classes()
    {
        $closure1 = static function ($string) {
            return $string;
        };

        $boo = new class() extends DynamicObject {
            private $foo;
        };
        $boo::addDynamicMethod('hello', $closure1);

        $closure2 = static function () {
            return 'hello';
        };
        $this->doDynamicRequest($closure2, [])->shouldReturn($boo->doDynamicRequest($closure1, ['hello']));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DynamicObject::class);
    }
}
