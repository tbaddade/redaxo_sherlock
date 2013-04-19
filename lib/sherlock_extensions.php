<?php

class sherlock_extensions
{

    public static function finder($params)
    {
        $finder = '';

        $classes = sherlock_finder::getAll();
        if (count($classes) > 0) {
            foreach ($classes as $class) {
                $class = 'sherlock_finder_' . $class;
                $sherlock = new $class();
                $finder .= $sherlock->getFirstFinderColumn();
            }
        }

        $panel = '<div id="sherlock-finder">' . $finder . '</div><span class="sherlock-finder">' . rex_i18n::msg('b_sherlock_title') . '</span>';
        $params['subject'] = str_replace('<div id="rex-navi-main"', $panel . '<div id="rex-navi-main"', $params['subject']);
        return $params['subject'];
    }
}
