<?php

class sherlock_finder
{
    /**
     * Classes array
     *
     * @var self[]
     */
    private static $classes = array();


    /**
     * Registers a class
     *
     * @param self $class
     */
    public static function register($class)
    {
        self::$classes[] = $class;
    }

    /**
     * Returns all registered classes
     *
     * @return self[]
     */
    public static function getAll()
    {
        sort(self::$classes);
        return self::$classes;
    }
}
