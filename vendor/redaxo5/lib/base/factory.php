<?php

/**
 * Factory base class
 *
 * Example child class:
 * <code>
 * class example extends rex_factory_base
 * {
 *   private function __construct($param)
 *   {
 *     // ...
 *   }
 *
 *   static public function factory($param)
 *   {
 *      $class = self::getFactoryClass();
 *     return new $class($param);
 *   }
 * }
 * </code>
 *
 * @author gharlan
 */
abstract class rex_factory_base
{
    /**
     * @var array
     */
    private static $classes = array();

    /**
     * Sets the class for the factory
     *
     * @param string $subclass Classname
     * @throws rex_exception
     */
    public static function setFactoryClass($subclass)
    {
        if (!is_string($subclass)) {
            throw new InvalidArgumentException('Expecting $subclass to be a string, ' . gettype($subclass) . ' given!');
        }
        $calledClass = get_called_class();
        if ($subclass != $calledClass && !is_subclass_of($subclass, $calledClass)) {
            throw new InvalidArgumentException('$class "' . $subclass . '" is expected to define a subclass of ' . $calledClass . '!');
        }
        self::$classes[$calledClass] = $subclass;
    }

    /**
     * Returns the class for the factory
     *
     * @return string
     */
    public static function getFactoryClass()
    {
        $calledClass = get_called_class();
        return isset(self::$classes[$calledClass]) ? self::$classes[$calledClass] : $calledClass;
    }

    /**
     * Returns if the class has a custom factory class
     *
     * @return boolean
     */
    public static function hasFactoryClass()
    {
        $calledClass = get_called_class();
        return isset(self::$classes[$calledClass]) && self::$classes[$calledClass] != $calledClass;
    }

    /**
     * Calls the factory class with the given method and arguments
     *
     * @param string $method    Method name
     * @param array  $arguments Array of arguments
     * @return mixed Result of the callback
     */
    protected static function callFactoryClass($method, array $arguments)
    {
        $class = static::getFactoryClass();
        return call_user_func_array(array($class, $method), $arguments);
    }
}
