<?php

class sherlock_load
{

    static function init()
    {
        if(!defined('APPLICATION_ENV')) {
            if(false === stripos($_SERVER['SERVER_NAME'], 'localhost')) {
                define('APPLICATION_ENV', 'development');
            } else {
                define('APPLICATION_ENV', 'production');
            }
        }

        if (!defined('SHERLOCK_CACHE_FILE_ADDONS_CHECKED')) {
            define('SHERLOCK_CACHE_FILE_ADDONS_CHECKED', rex_path::addonCache('sherlock', 'sherlock_addons_checked.php'));
        }
        if (!defined('SHERLOCK_CACHE_FILE_EXTENSION_POINTS')) {
            define('SHERLOCK_CACHE_FILE_EXTENSION_POINTS', rex_path::addonCache('sherlock', 'sherlock_extension_points.php'));
        }


        $myaddon = 'sherlock';

        // Klassen laden -------------------------------------------------------
        $dirs = array();
        $dirs[rex_path::addon($myaddon, 'lib')] = true;

        $scan = new b_scan_directory();
        $scan->addDirectories($dirs);
        $files = $scan->get();

        if (count($files) > 0) {
            foreach ($files as $file) {
                require_once $file;
            }
        }

    }

    static function check_install()
    {
        global $REX;

        // Einstellungen -------------------------------------------------------
        $basedir = $REX['INCLUDE_PATH'] . '/addons/sherlock';
        $myaddon = 'sherlock';

        // Check AddOns und Versionen ------------------------------------------
        require_once $basedir . '/vendor/b/lib/check.php';

        $min_php_version    = '5.4';
        $min_redaxo_version = '4.5';
        $addons_needed      = array();

        if (b_check::install($min_redaxo_version, $min_php_version, $addons_needed)) {
            $REX['ADDON']['install'][$myaddon] = 1;
        } else {
            $REX['ADDON']['installmsg'][$myaddon] = '&nbsp;';
        }
    }
}
