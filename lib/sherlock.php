<?php

class sherlock
{

    public static function registerAll()
    {
        sherlock_finder::register('extension_points');
        sherlock_finder::register('module');
        sherlock_finder::register('templates');
        sherlock_finder::register('rex_vars');
        sherlock_finder::register('sherlock');
    }
}
