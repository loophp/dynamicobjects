[![Build Status](https://www.travis-ci.org/drupol/dynamicobjects.svg?branch=master)](https://www.travis-ci.org/drupol/dynamicobjects)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/drupol/dynamicobjects/v/stable)](https://packagist.org/packages/drupol/dynamicobjects)
[![Total Downloads](https://poser.pugx.org/drupol/dynamicobjects/downloads)](https://packagist.org/packages/drupol/dynamicobjects)
[![License](https://poser.pugx.org/drupol/dynamicobjects/license)](https://packagist.org/packages/drupol/dynamicobjects)

# DynamicObjects

## Description

Create dynamic properties and methods on a PHP object.

## Features

* Can be used as object or as a trait,
* Can memoize methods results and/or properties if they are callable,
* The caching object use CacheInterface and can be injected.

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

Memoization:

```php
<?php

include 'vendor/autoload.php';

use drupol\DynamicObjects\DynamicObjectsTrait;

class myObject {
    use DynamicObjectsTrait;
}

$myObject = new myObject();

$object::addDynamicMethod('sleep', function($second = 5) {
  sleep($second);
  return true; // The function must return something to get the memoization working.
  }, true); // Set the last parameter to true to enable the memoization.

$object->sleep(); // The first execution will be executed and will last 5 seconds.
$object->sleep(); // The next executions, if arguments and method are the same will not be executed
$object->sleep(); // and only the result of the function will be returned.
$object->sleep();
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
 * @param bool $memoize
 *   Memoize parameter.
 */
DynamicObjectsTrait::addDynamicProperty($name, $value, $memoize = false);
```
```php
/**
 * Add a dynamic method.
 *
 * @param $name
 *   The method name.
 * @param \Closure $func
 *   The method.
 * @param bool $memoize
 *   Memoize parameter.
 */
DynamicObjectsTrait::addDynamicMethod($name, $func, $memoize = false);
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
```php
/**
 * Set the cache.
 *
 * @param \Psr\SimpleCache\CacheInterface $cache
 */
DynamicObjectsTrait::setDynamicObjectCacheProvider(CacheInterface $cache);
```
```php
/**
 * Get the cache.
 *
 * @return \Psr\SimpleCache\CacheInterface
 */
DynamicObjectsTrait::getDynamicObjectCacheProvider();
```
```php
/**
 * Clear the cache.
 */
DynamicObjectsTrait::clearDynamicObjectCache()
```


## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)

## Sponsors

* [ARhS Development](https://www.arhs-group.com)
* [European Commission - DIGIT](https://github.com/ec-europa)
