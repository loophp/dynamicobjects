<?php

namespace drupol\DynamicObjects;

/**
 * Trait DynamicObjectsTrait.
 */
trait DynamicObjectsTrait
{
    /**
     * @var array
     */
    protected static $dynamicMethods = [];

    /**
     * @var array
     */
    protected static $dynamicProperties = [];

    /**
     * {@inheritdoc}
     *
     * @param string $method
     *   The method name
     * @param array $parameters
     *   The parameters passed to the method
     *
     * @return mixed
     *   The result of the call
     */
    public function __call($method, array $parameters = [])
    {
        if ($data = static::getDynamicMethod($method)) {
            return $this->doDynamicRequest($data['factory'], $parameters);
        }

        throw new \BadMethodCallException(\sprintf('Undefined method: %s().', $method));
    }

    /**
     * {@inheritdoc}
     *
     * @param string $method
     *   The method name
     * @param array $parameters
     *   The method parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, array $parameters = [])
    {
        $instance = new static();

        if (null !== ($data = static::getDynamicMethod($method)) && true === $data['static']) {
            return $instance->doDynamicRequest($data['factory'], $parameters);
        }

        throw new \BadMethodCallException(\sprintf('Undefined static method: %s().', $method));
    }

    /**
     * {inheritdoc}.
     *
     * @param string $property
     *   The property name
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($data = static::getDynamicProperty($property)) {
            if ($data['factory'] instanceof \Closure) {
                return $this->doDynamicRequest($data['factory'], []);
            }

            return $data['factory'];
        }

        throw new \DomainException(\sprintf('Undefined property: %s.', $property));
    }

    /**
     * {inheritdoc}.
     *
     * @param string $property
     *   The property name
     * @param mixed $value
     *   The property value
     */
    public function __set($property, $value)
    {
        if (static::hasDynamicProperty($property)) {
            static::addDynamicProperty($property, $value);
        }
    }

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
    public static function addDynamicMethod($names, callable $callable, $static = false)
    {
        $class = static::class;

        foreach ((array) $names as $method_name) {
            static::$dynamicMethods[$class][$method_name] = [
                'name' => $method_name,
                'factory' => $callable,
                'static' => $static,
            ];
        }
    }

    /**
     * Add a dynamic property.
     *
     * @param array|string $names
     *   The property name or an array of property names
     * @param mixed $value
     *   The property value
     */
    public static function addDynamicProperty($names, $value)
    {
        foreach ((array) $names as $property) {
            static::$dynamicProperties[static::class][$property] = [
                'name' => $property,
                'factory' => $value,
            ];
        }
    }

    /**
     * Clear dynamic methods.
     */
    public static function clearDynamicMethods()
    {
        static::$dynamicMethods[static::class] = [];
    }

    /**
     * Clear dynamic properties.
     */
    public static function clearDynamicProperties()
    {
        static::$dynamicProperties[static::class] = [];
    }

    /**
     * Execute a closure.
     *
     * @param callable $callable
     *   The callable
     * @param array $parameters
     *   The closure's parameters
     *
     * @throws \ReflectionException
     *
     * @return null|mixed
     *   The return of the closure
     */
    public function doDynamicRequest(callable $callable, array $parameters = [])
    {
        $class = static::class;
        $reflexion = new \ReflectionClass($class);

        if ($callable instanceof \Closure) {
            if (!$reflexion->isAnonymous()) {
                $callable = $callable->bindTo($this, $class);
            }
        }

        return $callable(...$parameters);
    }

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
    public function extend($extensions)
    {
        if (\is_string($extensions) && \file_exists($extensions)) {
            $extensions = include $extensions;
        }

        if ($extensions instanceof \Closure) {
            $extensions($this);

            return $this;
        }

        throw new \InvalidArgumentException(
            'DynamicObjectsTrait::extend() requires a callable or a file that returns one.'
        );
    }

    /**
     * Get a dynamic method.
     *
     * @param string $name
     *   The method name
     *
     * @return null|array
     *   The method data if it exists, null otherwise
     */
    public static function getDynamicMethod($name)
    {
        return (static::hasDynamicMethod($name)) ?
            static::$dynamicMethods[static::class][$name] :
            null;
    }

    /**
     * Get a dynamic property.
     *
     * @param string $name
     *   The property name
     *
     * @return null|mixed
     *   The property value if it exists, null otherwise
     */
    public static function getDynamicProperty($name)
    {
        return (static::hasDynamicProperty($name)) ?
            static::$dynamicProperties[static::class][$name] :
            null;
    }

    /**
     * Check if a dynamic method exists.
     *
     * @param string $name
     *   The property name
     *
     * @return bool
     *   True if the property exists, false otherwise
     */
    public static function hasDynamicMethod($name)
    {
        return isset(static::$dynamicMethods[static::class][$name]);
    }

    /**
     * Check if a dynamic property exists.
     *
     * @param string $name
     *   The property name
     *
     * @return bool
     *   True if the property exists, false otherwise
     */
    public static function hasDynamicProperty($name)
    {
        return isset(static::$dynamicProperties[static::class][$name]);
    }

    /**
     * Remove a dynamic method.
     *
     * @param string $name
     *   The method name
     */
    public static function removeDynamicMethod($name)
    {
        unset(static::$dynamicMethods[static::class][$name]);
    }

    /**
     * Remove a dynamic property.
     *
     * @param string $name
     *   The property name
     */
    public static function removeDynamicProperty($name)
    {
        unset(static::$dynamicProperties[static::class][$name]);
    }
}
