<?php

namespace drupol\DynamicObjects;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ArrayCache;

/**
 * Trait DynamicObjectsTrait.
 *
 * @package drupol\DynamicObjects
 */
trait DynamicObjectsTrait
{
    /**
     * @var array
     */
    protected static $dynamicMethods = array();

    /**
     * @var array
     */
    protected static $dynamicProperties = array();

    /**
     * @var CacheInterface
     */
    protected static $cache;

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
    public static function addDynamicProperty($name, $value, $memoize = false)
    {
        static::$dynamicProperties[get_called_class()][$name] = [
          'name' => $name,
          'factory' => $value,
          'memoize' => $memoize,
        ];
    }

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
    public static function addDynamicMethod($name, \Closure $func, $memoize = false)
    {
        static::$dynamicMethods[get_called_class()][$name] = [
          'name' => $name,
          'factory' => $func,
          'memoize' => $memoize,
        ];
    }

    /**
     * Check if a dynamic property exists.
     *
     * @param string $name
     *   The property name.
     * @return bool
     *   True if the property exists, false otherwise.
     */
    public static function hasDynamicProperty($name)
    {
        return isset(static::$dynamicProperties[get_called_class()][$name]);
    }

    /**
     * Check if a dynamic method exists.
     *
     * @param string $name
     *   The property name.
     * @return bool
     *   True if the property exists, false otherwise.
     */
    public static function hasDynamicMethod($name)
    {
        return isset(static::$dynamicMethods[get_called_class()][$name]);
    }

    /**
     * Get a dynamic property.
     *
     * @param $name
     *   The property name.
     * @return mixed|null
     *   The property value if it exists, null otherwise.
     */
    public static function getDynamicProperty($name)
    {
        if (static::hasDynamicProperty($name)) {
            return static::$dynamicProperties[get_called_class()][$name];
        }

        return null;
    }

    /**
     * Get a dynamic method.
     *
     * @param $name
     *   The method name.
     * @return mixed|null
     *   The method if it exists, null otherwise.
     */
    public static function getDynamicMethod($name)
    {
        return ( static::hasDynamicMethod($name) ) ?
          static::$dynamicMethods[get_called_class()][$name] : null;
    }

    /**
     * Clear dynamic properties.
     */
    public static function clearDynamicProperties()
    {
        static::$dynamicProperties[get_called_class()] = array();
    }

    /**
     * Clear dynamic methods.
     */
    public static function clearDynamicMethods()
    {
        static::$dynamicMethods[get_called_class()] = array();
    }

    /**
     * Remove a dynamic property.
     *
     * @param string $name
     *   The property name.
     */
    public static function removeDynamicProperty($name)
    {
        unset(static::$dynamicProperties[get_called_class()][$name]);
    }

    /**
     * Remove a dynamic method.
     *
     * @param string $name
     *   The method name.
     */
    public static function removeDynamicMethod($name)
    {
        unset(static::$dynamicMethods[get_called_class()][$name]);
    }

    /**
     * Set the cache.
     *
     * @param \Psr\SimpleCache\CacheInterface $cache
     */
    public static function setDynamicObjectCacheProvider(CacheInterface $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Get the cache.
     *
     * @return \Psr\SimpleCache\CacheInterface
     */
    public static function getDynamicObjectCacheProvider()
    {
        if (!isset(self::$cache)) {
            self::setDynamicObjectCacheProvider(new ArrayCache());
        }

        return self::$cache;
    }

    /**
     * Clear the cache.
     */
    public static function clearDynamicObjectCache()
    {
        self::getDynamicObjectCacheProvider()->clear();
    }

    /**
     * @param $method
     * @param array $parameters
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __call($method, array $parameters = array())
    {
        if (static::hasDynamicMethod($method)) {
            $data = static::getDynamicMethod($method);
            $cacheid =  sha1(get_called_class() . serialize(func_get_args()));

            if (true === $data['memoize']) {
                if ($result = self::getDynamicObjectCacheProvider()->get($cacheid)) {
                    return $result;
                }
            }

            $result = call_user_func_array($data['factory']->bindTo($this, $this), $parameters);

            if (true === $data['memoize']) {
                self::getDynamicObjectCacheProvider()->set($cacheid, $result);
            }

            return $result;
        }

        throw new \BadMethodCallException(sprintf('Undefined method: %s().', $method));
    }

    /**
     * {inheritdoc}
     */
    public function __set($property, $value)
    {
        if (static::hasDynamicProperty($property)) {
            static::addDynamicProperty($property, $value);
        }
    }

    /**
     * {inheritdoc}
     */
    public function __get($property)
    {
        if (static::hasDynamicProperty($property)) {
            $data = static::getDynamicProperty($property);

            if (is_callable($data['factory'])) {
                $cacheid =  sha1(get_called_class() . serialize(func_get_args()));

                if (true == $data['memoize']) {
                    if ($result = self::getDynamicObjectCacheProvider()->get($cacheid)) {
                        return $result;
                    }
                }

                $result = call_user_func($data['factory']->bindTo($this, $this));

                if (true === $data['memoize']) {
                    self::getDynamicObjectCacheProvider()->set($cacheid, $result);
                }

                return $result;
            }

            return $data['factory'];
        }

        throw new \DomainException(sprintf('Undefined property: %s().', $property));
    }
}
