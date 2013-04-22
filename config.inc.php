<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */


$basedir = __DIR__;
$myaddon = ltrim(substr($basedir, strrpos($basedir, '/')), DIRECTORY_SEPARATOR);



// Sprachdateien anhaengen
// muss wegen Developer-AddOn-Block extra sein
if ($REX['REDAXO']) {
    $I18N->appendFile($basedir . '/lang/');
}



$REX['ADDON']['rxid'][$myaddon] = '';
//$REX['ADDON']['name'][$myaddon] = $I18N->msg('b_sherlock_title');

// Credits
$REX['ADDON']['version'][$myaddon]     = '0.0';
$REX['ADDON']['author'][$myaddon]      = 'blumbeet - web.studio';
$REX['ADDON']['supportpage'][$myaddon] = '';
$REX['ADDON']['perm'][$myaddon]        = 'admin[]';
//$REX['ADDON']['navigation'][$myaddon]  = array('block' => 'developer');



// Check AddOns und Versionen --------------------------------------------------
if (OOAddon::isActivated($myaddon)) {

    require_once($basedir . '/lib/' . $myaddon . '_load.php');
    require_once($basedir . '/vendor/init.php');

    if (rex::getUser()) {

        $myclass = $myaddon . '_load';
        $myclass::init();

        rex_register_extension('PAGE_TITLE', $myaddon . '_load::check_install');

        $page       = rex_request('page', 'string');
        $activate   = rex_request('activate', 'int', -1);
        if ($page == 'addon' && $activate >= 0) {
            rex_file::delete(SHERLOCK_CACHE_FILE_ADDONS_CHECKED);
            rex_file::delete(SHERLOCK_CACHE_FILE_EXTENSION_POINTS);
        }


        rex_register_extension('ADDONS_INCLUDED', $myaddon . '::registerAll');
        rex_register_extension('OUTPUT_FILTER', 'sherlock_extensions::finder');

        $finder = rex_request('sherlock_finder', 'string');
        if ($finder) {
            $class = 'sherlock_finder_'. $finder;
            $sherlock = new $class();
            $sherlock->getFinderColumn();
        }

        rex_view::addCssFile(rex_url::addonAssets('sherlock', 'sherlock.css'));

        rex_view::addJsFile(rex_url::addonAssets('sherlock', 'columnview.js'));
        rex_view::addJsFile(rex_url::addonAssets('sherlock', 'sherlock.js'));

    }
}
