[![Build Status](https://www.travis-ci.org/drupol/dynamicobjects.svg?branch=master)](https://www.travis-ci.org/drupol/dynamicobjects)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/drupol/dynamicobjects/v/stable)](https://packagist.org/packages/drupol/dynamicobjects)
[![Total Downloads](https://poser.pugx.org/drupol/dynamicobjects/downloads)](https://packagist.org/packages/drupol/dynamicobjects)
[![License](https://poser.pugx.org/drupol/dynamicobjects/license)](https://packagist.org/packages/drupol/dynamicobjects)

# DynamicObjects

## Description

Create dynamic properties and methods on a PHP object.

## Installation

`composer require drupol/dynamicobjects
`
## Usage

Using the object:

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

Using the trait:

```php
<?php

include 'vendor/autoload.php';

use drupol\DynamicObjects\DynamicObjectsTrait;

class myObject {
    use DynamicObjectsTrait;
}

$myObject = new myObject();

$myObject::addDynamicProperty('name', 'DynamicObjects');
echo $myObject->name; // DynamicObjects

$myObject::addDynamicMethod('sayHelloWorld', function() {echo "Hello world!";});
$myObject->sayHelloWorld(); // Hello world!
```

## API

DynamicObjects provides a PHP trait and an Object depending on it.

You can use it by extending the object or by using the trait directly, there is no difference.

It exposes the following static methods:

```php
/**
 * Add a dynamic property.
 *
 * @param string $name
 *   The property name.
 * @param mixed $value
 *   The property value.
 */
DynamicObjectsTrait::addDynamicProperty($name, $value);
```
```php
/**
 * Add a dynamic method.
 *
 * @param $name
 *   The method name.
 * @param \Closure $func
 *   The method.
 */
DynamicObjectsTrait::addDynamicMethod($name, $func);
```
```php
/**
 * Check if a dynamic property exists.
 *
 * @param string $name
 *   The property name.
 * @return bool
 *   True if the property exists, false otherwise.
 */
DynamicObjectsTrait::hasDynamicProperty($name);
```
```php
/**
 * Check if a dynamic method exists.
 *
 * @param string $name
 *   The property name.
 * @return bool
 *   True if the property exists, false otherwise.
 */
DynamicObjectsTrait::hasDynamicMethod($name);
```
```php
/**
 * Get a dynamic property.
 *
 * @param $name
 *   The property name.
 * @return mixed|null
 *   The property value if it exists, null otherwise.
 */
DynamicObjectsTrait::getDynamicProperty($name);
```
```php
/**
 * Get a dynamic method.
 *
 * @param $name
 *   The method name.
 * @return mixed|null
 *   The method if it exists, null otherwise.
 */
DynamicObjectsTrait::getDynamicMethod($name);
```
```php
/**
 * Remove a dynamic property.
 *
 * @param string $name
 *   The property name.
 */
DynamicObjectsTrait::removeDynamicProperty($name);
```
```php
/**
 * Remove a dynamic method.
 *
 * @param string $name
 *   The method name.
 */
DynamicObjectsTrait::removeDynamicMethod($name);
```
```php
/**
 * Clear dynamic properties.
 */
DynamicObjectsTrait::clearDynamicProperties();
```
```php
/**
 * Clear dynamic methods.
 */
DynamicObjectsTrait::clearDynamicMethods();
```

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)

## Sponsors

* [ARhS Development](https://www.arhs-group.com)
* [European Commission - DIGIT](https://github.com/ec-europa)
