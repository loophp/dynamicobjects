[![Build Status](https://www.travis-ci.org/drupol/dynamicobjects.svg?branch=master)](https://www.travis-ci.org/drupol/dynamicobjects)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/drupol/dynamicobjects/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/drupol/dynamicobjects/?branch=master)
[![StyleCI](https://styleci.io/repos/103787075/shield?branch=master)](https://styleci.io/repos/103787075)
[![Latest Stable Version](https://poser.pugx.org/drupol/dynamicobjects/v/stable)](https://packagist.org/packages/drupol/dynamicobjects)
[![Total Downloads](https://poser.pugx.org/drupol/dynamicobjects/downloads)](https://packagist.org/packages/drupol/dynamicobjects)
[![License](https://poser.pugx.org/drupol/dynamicobjects/license)](https://packagist.org/packages/drupol/dynamicobjects)

# DynamicObjects

## Description

Create and manage dynamic properties and methods on a PHP object.

## Features

* Allows you to add regular or static methods and properties to an existing object,
* Can be used by extending the DynamicObject object or as a trait, on a real or anonymous class,
* Can memoize methods results and/or properties if they are callable,
* The caching object use CacheInterface and can be injected.

## Requirements

* PHP >= 5.6

## Installation

`composer require drupol/dynamicobjects`

## Optional

To enable memoization you will need an extra package.

`composer require drupol/memoize`

## Usage

Using the object:

```php
<?php

include 'vendor/autoload.php';

// Anonymous classes creation is only available to PHP >= 7.
$myObject = new class extends \drupol\DynamicObjects\DynamicObject {};

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

// Anonymous classes creation is only available to PHP >= 7.
$myObject = new class {
    use DynamicObjectsTrait;
};

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

// Anonymous classes creation is only available to PHP >= 7.
$myObject = new class {
    use DynamicObjectsTrait;
};

$myObject::addDynamicMethod('sleep', function($second = 5) {
  sleep($second);
  return true; // The function must return something to get the memoization working.
  }, true); // Set the last parameter to true to enable the memoization.

$myObject->sleep(); // The first execution will be executed and will last 5 seconds.
$myObject->sleep(); // The next executions, if arguments and method are the same will not be executed
$myObject->sleep(); // and only the result of the function will be returned.
$myObject->sleep();
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
 * @param bool $static
 *   Static flag parameter.
 */
DynamicObjectsTrait::addDynamicMethod($name, $func, $memoize = false, $static = false);
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
