<?php

// tab :: Pfad angepasst <- suche nach diesem Kommentar


/**
 * Utility class to generate absolute paths
 *
 * @author gharlan
 *
 * @package redaxo5
 */
class rex_path
{
    protected static
        $base,
        $backend;

    public static function init($htdocs, $backend)
    {
        self::$base = realpath($htdocs) . '/';
        self::$backend = $backend;
    }

    /**
     * Returns a base path
     */
    public static function base($file = '')
    {
        return strtr(self::$base . $file, '/\\', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
    }

    /**
     * Returns the path to the frontend
     */
    public static function frontend($file = '')
    {
        return self::base($file);
    }

    /**
     * Returns the path to the frontend-controller (index.php from frontend)
     */
    public static function frontendController()
    {
        return self::base('index.php');
    }

    /**
     * Returns the path to the backend
     */
    public static function backend($file = '')
    {
        return self::base(self::$backend . '/' . $file);
    }

    /**
     * Returns the path to the backend-controller (index.php from backend)
     */
    public static function backendController()
    {
        return self::backend('index.php');
    }

    /**
     * Returns the path to the media-folder
     */
    public static function media($file = '')
    {
        // tab :: Pfad angepasst
        // return self::base('media/' . $file);
        return self::base('files/' . $file);
    }

    /**
     * Returns the path to the assets folder of the core, which contains all assets required by the core to work properly.
     */
    public static function assets($file = '')
    {
        // tab :: Pfad angepasst
        // return self::base('assets/' . $file);
        return self::base('files/' . $file);
    }

    /**
     * Returns the path to the assets folder of the given addon, which contains all assets required by the addon to work properly.
     *
     * @see assets()
     */
    public static function addonAssets($addon, $file = '')
    {
        return self::assets('addons/' . $addon . '/' . $file);
    }

    /**
     * Returns the path to the assets folder of the given plugin of the given addon
     *
     * @see assets()
     */
    public static function pluginAssets($addon, $plugin, $file = '')
    {
        return self::addonAssets($addon, 'plugins/' . $plugin . '/' . $file);
    }

    /**
     * Returns the path to the data folder of the core.
     */
    public static function data($file = '')
    {
        return self::backend('data/' . $file);
    }

    /**
     * Returns the path to the data folder of the given addon.
     */
    public static function addonData($addon, $file = '')
    {
        return self::data('addons/' . $addon . '/' . $file);
    }

    /**
     * Returns the path to the data folder of the given plugin of the given addon.
     */
    public static function pluginData($addon, $plugin, $file = '')
    {
        return self::addonData($addon, 'plugins/' . $plugin . '/' . $file);
    }

    /**
     * Returns the path to the cache folder of the core
     */
    public static function cache($file = '')
    {
        // tab :: Pfad angepasst
        // return self::backend('cache/' . $file);
        return self::backend('include/generated/files/' . $file);
    }

    /**
     * Returns the path to the cache folder of the given addon.
     */
    public static function addonCache($addon, $file = '')
    {
        // tab :: Pfad angepasst
        // return self::cache('addons/' . $addon . '/' . $file);
        return self::cache($file);
    }

    /**
     * Returns the path to the cache folder of the given plugin
     */
    public static function pluginCache($addon, $plugin, $file = '')
    {
        // tab :: Pfad angepasst
        // return self::addonCache($addon, 'plugins/' . $plugin . '/' . $file);
        return self::cache($file);
    }

    /**
     * Returns the path to the src folder.
     */
    public static function src($file = '')
    {
        // tab :: Pfad angepasst
        // return self::backend('src/' . $file);
        return self::backend('include/' . $file);
    }

    /**
     * Returns the path to the actual core
     */
    public static function core($file = '')
    {
        // tab :: Pfad angepasst
        // return self::src('core/' . $file);
        return self::src('addons/redaxo5/vendor/redaxo5/' . $file);
    }

    /**
     * Returns the base path to the folder of the given addon
     */
    public static function addon($addon, $file = '')
    {
        return self::src('addons/' . $addon . '/' . $file);
    }

    /**
     * Returns the base path to the folder of the plugin of the given addon
     */
    public static function plugin($addon, $plugin, $file = '')
    {
        return self::addon($addon, 'plugins/' . $plugin . '/' . $file);
    }

    /**
     * Converts a relative path to an absolute
     *
     * @param string $relPath The relative path
     *
     * @return string Absolute path
     */
    public static function absolute($relPath)
    {
        $stack = array();

        // pfadtrenner vereinheitlichen
        $relPath = str_replace('\\', '/', $relPath);
        foreach (explode('/', $relPath) as $dir) {
            // Aktuelles Verzeichnis, oder Ordner ohne Namen
            if ($dir == '.' || $dir == '')
                continue;

            // Zum Parent
            if ($dir == '..')
                array_pop($stack);
            // Normaler Ordner
            else
                array_push($stack, $dir);
        }

        return implode(DIRECTORY_SEPARATOR, $stack);
    }
}
