<?php

namespace drupol\DynamicObjects\test;

class ExampleClass
{

    public $publicProperty = 'publicProperty';
    private $privateProperty = 'privateProperty';
    protected $protectedProperty = 'protectedProperty';

    public function publicMethod()
    {
        return 'publicMethod';
    }

    private function privateMethod()
    {
        return 'privateMethod';
    }

    protected function protectedMethod()
    {
        return 'protectedMethod';
    }

    public function renderProperties() {
        return $this->publicProperty . $this->privateProperty . $this->protectedProperty;
    }

    public function renderMethods() {
        return $this->publicMethod() . $this->privateMethod() . $this->protectedMethod();
    }
}
