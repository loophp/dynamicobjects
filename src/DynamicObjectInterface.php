<?php

namespace drupol\DynamicObjects;

/**
 * Interface DynamicObjectInterface
 */
interface DynamicObjectInterface
{
    /**
     * Add a dynamic property.
     *
     * @param string|array $names
     *   The property name or an array of property names.
     * @param mixed $value
     *   The property value.
     * @param bool $memoize
     *   Memoize parameter.
     */
    public static function addDynamicProperty($names, $value, $memoize = false);

    /**
     * Add a dynamic method.
     *
     * @param string|array $names
     *   The method name or an array of method names.
     * @param callable $callable
     *   The method.
     * @param bool $memoize
     *   Memoize parameter.
     * @param bool $static
     *   Static flag parameter.
     */
    public static function addDynamicMethod($names, callable $callable, $memoize = false, $static = false);

    /**
     * Check if a dynamic property exists.
     *
     * @param string $name
     *   The property name.
     * @return bool
     *   True if the property exists, false otherwise.
     */
    public static function hasDynamicProperty($name);

    /**
     * Check if a dynamic method exists.
     *
     * @param string $name
     *   The property name.
     * @return bool
     *   True if the property exists, false otherwise.
     */
    public static function hasDynamicMethod($name);

    /**
     * Get a dynamic property.
     *
     * @param string $name
     *   The property name.
     * @return mixed|null
     *   The property value if it exists, null otherwise.
     */
    public static function getDynamicProperty($name);

    /**
     * Get a dynamic method.
     *
     * @param string $name
     *   The method name.
     * @return array|null
     *   The method data if it exists, null otherwise.
     */
    public static function getDynamicMethod($name);

    /**
     * Clear dynamic properties.
     */
    public static function clearDynamicProperties();

    /**
     * Clear dynamic methods.
     */
    public static function clearDynamicMethods();

    /**
     * Remove a dynamic property.
     *
     * @param string $name
     *   The property name.
     */
    public static function removeDynamicProperty($name);

    /**
     * Remove a dynamic method.
     *
     * @param string $name
     *   The method name.
     */
    public static function removeDynamicMethod($name);

    /**
     * Execute a closure.
     *
     * @param callable $callable
     *   The closure.
     * @param array $parameters
     *   The closure's parameters.
     * @param bool $memoize
     *   The memoize parameter.
     *
     * @return mixed|null
     *   The return of the closure.
     */
    public function doDynamicRequest(callable $callable, array $parameters = [], $memoize = false);

    /**
     * Extend the dynamic object.
     *
     * @param string|callable $extensions
     *   A file that returns a callable or a callable.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function extend($extensions);
}
