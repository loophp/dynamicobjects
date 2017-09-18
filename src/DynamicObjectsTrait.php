<?php

namespace drupol\DynamicObjects;

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
     * Add a dynamic property.
     *
     * @param string $name
     *   The property name.
     * @param mixed $value
     *   The property value.
     *
     */
    public static function addDynamicProperty($name, $value)
    {
        static::$dynamicProperties[get_called_class()][$name] = $value;
    }

    /**
     * Add a dynamic method.
     *
     * @param $name
     *   The method name.
     * @param \Closure $func
     *   The method.
     */
    public static function addDynamicMethod($name, \Closure $func)
    {
        static::$dynamicMethods[get_called_class()][$name] = $func;
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
     * {inheritdoc}
     */
    public function __call($method, array $parameters = array())
    {
        if (static::hasDynamicMethod($method)) {
            return static::getDynamicMethod($method)->bindTo($this, get_called_class())->__invoke($parameters);
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
            $value = static::getDynamicProperty($property);

            if (is_callable($value)) {
                $value = call_user_func_array(
                    $value->bindTo($this, get_called_class()),
                    []
                );
            }

            return $value;
        }
    }
}
