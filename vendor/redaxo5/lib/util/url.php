<?php

// tab :: Pfad angepasst <- suche nach diesem Kommentar

/**
 * Utility class to generate relative URLs
 *
 * @author gharlan
 *
 * @package redaxo5
 */
class rex_url
{
    protected static
        $base,
        $backend;

    public static function init($htdocs, $backend)
    {
        self::$base = $htdocs;
        self::$backend = substr($htdocs, -3) === '../' ? '' : $htdocs . $backend . '/';
    }
    /**
     * Returns a base url
     */
    public static function base($file = '')
    {
        return htmlspecialchars(self::$base . $file);
    }

    /**
     * Returns the url to the frontend
     */
    public static function frontend($file = '')
    {
        return self::base($file);
    }

    /**
     * Returns the url to the frontend-controller (index.php from frontend)
     */
    public static function frontendController(array $params = array())
    {
        $query = rex_string::buildQuery($params);
        $query = $query ? '?' . $query : '';
        return self::base('index.php' . $query);
    }

    /**
     * Returns the url to the backend
     */
    public static function backend($file = '')
    {
        return htmlspecialchars(self::$backend . $file);
    }

    /**
     * Returns the url to the backend-controller (index.php from backend)
     */
    public static function backendController(array $params = array())
    {
        $query = rex_string::buildQuery($params);
        $query = $query ? '?' . $query : '';
        return self::backend('index.php' . $query);
    }

    /**
     * Returns the url to a backend page
     */
    public static function backendPage($page, array $params = array())
    {
        return self::backendController(array_merge(array('page' => $page), $params));
    }

    /**
     * Returns the url to the current backend page
     */
    public static function currentBackendPage(array $params = array())
    {
        return self::backendPage(rex_be_controller::getCurrentPage(), $params);
    }

    /**
     * Returns the url to the media-folder
     */
    public static function media($file = '')
    {
        // tab :: Pfad angepasst
        // return self::base('media/' . $file);
        return self::base('files/' . $file);
    }

    /**
     * Returns the url to the assets folder of the core, which contains all assets required by the core to work properly.
     */
    public static function assets($file = '')
    {
        // tab :: Pfad angepasst
        // return self::base('assets/' . $file);
        return self::base('files/' . $file);
    }

    /**
     * Returns the url to the assets folder of the given addon, which contains all assets required by the addon to work properly.
     *
     * @see assets()
     */
    public static function addonAssets($addon, $file = '')
    {
        // tab :: Pfad angepasst
        return self::assets('addons/' . $addon . '/' . $file);
    }

    /**
     * Returns the url to the assets folder of the given plugin of the given addon
     *
     * @see assets()
     */
    public static function pluginAssets($addon, $plugin, $file = '')
    {
        // tab :: Pfad angepasst
        return self::addonAssets($addon, 'plugins/' . $plugin . '/' . $file);
    }
}
