<?php

namespace drupol\DynamicObjects;

/**
 * Interface DynamicObjectInterface.
 */
interface DynamicObjectInterface
{
    /**
     * Add a dynamic method.
     *
     * @param array|string $names
     *   The method name or an array of method names
     * @param callable $callable
     *   The method
     * @param bool $static
     *   Static flag parameter
     */
    public static function addDynamicMethod($names, callable $callable, $static = false);

    /**
     * Add a dynamic property.
     *
     * @param array|string $names
     *   The property name or an array of property names
     * @param mixed $value
     *   The property value
     */
    public static function addDynamicProperty($names, $value);

    /**
     * Clear dynamic methods.
     */
    public static function clearDynamicMethods();

    /**
     * Clear dynamic properties.
     */
    public static function clearDynamicProperties();

    /**
     * Execute a closure.
     *
     * @param callable $callable
     *   The closure
     * @param array $parameters
     *   The closure's parameters
     *
     * @return null|mixed
     *   The return of the closure
     */
    public function doDynamicRequest(callable $callable, array $parameters = []);

    /**
     * Extend the dynamic object.
     *
     * @param callable|string $extensions
     *   A file that returns a callable or a callable
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function extend($extensions);

    /**
     * Get a dynamic method.
     *
     * @param string $name
     *   The method name
     *
     * @return null|array
     *   The method data if it exists, null otherwise
     */
    public static function getDynamicMethod($name);

    /**
     * Get a dynamic property.
     *
     * @param string $name
     *   The property name
     *
     * @return null|mixed
     *   The property value if it exists, null otherwise
     */
    public static function getDynamicProperty($name);

    /**
     * Check if a dynamic method exists.
     *
     * @param string $name
     *   The property name
     *
     * @return bool
     *   True if the property exists, false otherwise
     */
    public static function hasDynamicMethod($name);

    /**
     * Check if a dynamic property exists.
     *
     * @param string $name
     *   The property name
     *
     * @return bool
     *   True if the property exists, false otherwise
     */
    public static function hasDynamicProperty($name);

    /**
     * Remove a dynamic method.
     *
     * @param string $name
     *   The method name
     */
    public static function removeDynamicMethod($name);

    /**
     * Remove a dynamic property.
     *
     * @param string $name
     *   The property name
     */
    public static function removeDynamicProperty($name);
}
