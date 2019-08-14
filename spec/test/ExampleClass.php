<?php

declare(strict_types=1);

namespace drupol\DynamicObjects\test;

class ExampleClass
{
    public $publicProperty = 'publicProperty';

    protected $protectedProperty = 'protectedProperty';

    private $privateProperty = 'privateProperty';

    public function publicMethod()
    {
        return 'publicMethod';
    }

    public function renderMethods()
    {
        return $this->publicMethod() . $this->privateMethod() . $this->protectedMethod();
    }

    public function renderProperties()
    {
        return $this->publicProperty . $this->privateProperty . $this->protectedProperty;
    }

    protected function protectedMethod()
    {
        return 'protectedMethod';
    }

    private function privateMethod()
    {
        return 'privateMethod';
    }
}
