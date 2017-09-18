[![Build Status](https://www.travis-ci.org/drupol/dynamicobjects.svg?branch=master)](https://www.travis-ci.org/drupol/dynamicobjects)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/drupol/dynamicobjects/v/stable)](https://packagist.org/packages/drupol/dynamicobjects)
[![Total Downloads](https://poser.pugx.org/drupol/dynamicobjects/downloads)](https://packagist.org/packages/drupol/dynamicobjects)
[![License](https://poser.pugx.org/drupol/dynamicobjects/license)](https://packagist.org/packages/drupol/dynamicobjects)

# DynamicObjects

## Description

Create dynamic properties and methods on a PHP object.

## Examples

```php
<?php

include 'vendor/autoload.php';

class myObject extends \drupol\DynamicObjects\DynamicObject {

}

$myObject = new myObject();

$myObject::addDynamicProperty('name', 'DynamicObjects');
echo $myObject->name; // DynamicObjects

$myObject::addDynamicMethod('sayHelloWorld', function() {echo "Hello world!";});
$myObject->sayHelloWorld(); // Hello world!

```

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)
