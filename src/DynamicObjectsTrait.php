<?php

namespace drupol\DynamicObjects;

use drupol\Memoize\MemoizeTrait;

/**
 * Trait DynamicObjectsTrait.
 *
 * @package drupol\DynamicObjects
 */
trait DynamicObjectsTrait
{
    use MemoizeTrait;

    /**
     * @var array
     */
    protected static $dynamicMethods = array();

    /**
     * @var array
     */
    protected static $dynamicProperties = array();

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
     * @param bool $static
     *   Static flag parameter.
     */
    public static function addDynamicMethod($name, \Closure $func, $memoize = false, $static = false)
    {
        static::$dynamicMethods[get_called_class()][$name] = [
            'name' => $name,
            'factory' => $func,
            'memoize' => $memoize,
            'static' => $static,
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
     * Execute a closure.
     *
     * @param \Closure $func
     *   The closure.
     * @param array $parameters
     *   The closure's parameters.
     * @param bool $memoize
     *   The memoize parameter.
     *
     * @return mixed|null
     *   The return of the closure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function doDynamicRequest(\Closure $func, array $parameters = [], $memoize = false)
    {
        if (!class_exists('\drupol\Memoize\Memoize') || false === $memoize) {
            $memoize = false;
        }

        if (true === $memoize) {
            return $this->memoize($func, $parameters);
        }

        return call_user_func_array($func->bindTo($this, get_called_class()), $parameters);
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
        if ($data = static::getDynamicMethod($method)) {
            return $this->doDynamicRequest($data['factory'], $parameters, $data['memoize']);
        }

        throw new \BadMethodCallException(sprintf('Undefined method: %s().', $method));
    }

    /**
     * @param $method
     * @param array $parameters
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function __callStatic($method, array $parameters = array())
    {
        $instance = new static();

        if ($data = static::getDynamicMethod($method)) {
            if (true == $data['static']) {
                return $instance->doDynamicRequest($data['factory'], $parameters, $data['memoize']);
            }
        }

        throw new \BadMethodCallException(sprintf('Undefined static method: %s().', $method));
    }

    /**
     * {inheritdoc}
     */
    public function __get($property)
    {
        if ($data = static::getDynamicProperty($property)) {
            $return = $data['factory'];

            if (is_callable($data['factory'])) {
                $return = $this->doDynamicRequest($data['factory'], [], $data['memoize']);
            }

            return $return;
        }

        throw new \DomainException(sprintf('Undefined property: %s.', $property));
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
}
