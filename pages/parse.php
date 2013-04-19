<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */


// Standard Verzeichnisse ------------------------------------------------------
$dirs = [
    realpath($REX['HTDOCS_PATH'] . 'redaxo')    => false,
    $REX['INCLUDE_PATH']                        => false,
    //$REX['INCLUDE_PATH'] . '/addons'            => true,
    $REX['INCLUDE_PATH'] . '/classes'           => true,
    $REX['INCLUDE_PATH'] . '/functions'         => true,
    $REX['INCLUDE_PATH'] . '/layout'            => true,
    $REX['INCLUDE_PATH'] . '/pages'             => true,
];

// installierte Addons hinzufuegen ---------------------------------------------
$addons_checked = array();
$addons = OOAddon::getRegisteredAddons();
foreach ($addons as $addon) {

    if (OOAddon::isAvailable($addon)) {
        $dirs[$REX['INCLUDE_PATH'] . '/addons/' . $addon] = true;
        $addons_checked[$addon] = true;
    } else {
        $addons_checked[$addon] = false;
    }
}

// PHP Dateien holen -----------------------------------------------------------
$scan = new b_scan_directory();
$scan->addDirectories($dirs);
$scan->setExtension('php');
$files = $scan->get();


// Extension Points holen ------------------------------------------------------
$extensions = array();

$extension_pattern =  '@'
//.   '(?!(?://|/\*))\s?'
.   'rex_register_extension_point'                      # Start der Extension
.   '\s*'
.   '\('                                                # Extension Point öffnende Klammer
.   '\s*'
.   '(?:\'|")'                                          # Name steht in Hochkomma oder Anführungszeichen
.   '(?P<name>.+?)'                                     # Namen festhalten
.   '(?:\'|")'                                          # Name steht in Hochkomma oder Anführungszeichen
.   '(?:'                                               # Params können optional sein
.   '\s*?'
.   '(?:,\s*'
.   '(?P<subject>'
.   '(?(?=array)(?:array\([^)].*?\))|(?:.*?))'          # übergebene Variable kann ein array oder Variable sein
.   ')'
.   '\s*?)?'
.   '(?:,\s*'
.   '(?P<params>.*?)?'                                  # Param festhalten
.   '\s*?)?'
.   '(?:,\s?'
.   '(?P<readonly>true|false)'                          # readonly festhalten
.   '\s?)?'
.   ')?'                                                # Ende von optional Params
.   '\)'                                                # Extension Point schließende Klammer
.   '\s*'
.   '(?(?!\)\s*{)(;))'                                  # Ende des Extension Points ; oder {
.   '@is';


$extension_before_pattern = array(
    //'@\)\s*?{@is',
    //'@\)@is',
    '@,@is',
);
$extension_before_replace = array(
    //') {',
    //' )',
    ', ',
);


$extension_after_pattern = array(
    // säubern
    '@\'\s*array\s*\(\s*@is',
    '@\)\s*\'$@is',
    '@,\s*@',
    '@^\'\s*@',
    '@\'$@',
    '@\s*=>\s*@',
    '@\s*,?\s*\)$@',
    '@^\'{2}$@',
    '@\s{2}@',
    // formatieren
    '@array\(@',
    '@array\(\s*\)@',
    '@, @',
    '@=>@',
);



$extension_after_replace = array(
    // säubern
    'array(',
    ')',
    ', ',
    '  ',
    '',
    '=>',
    ')',
    '',
    '',
    // formatieren
    'array(' . "\n  ",
    'array()',
    ', ' . "\n  ",
    ' => ',
);


foreach ($files as $file) {

    $content = file_get_contents($file);

    //$content = preg_replace($extension_before_pattern, $extension_before_replace, $content);
    preg_match_all($extension_pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    if (count($matches) > 0) {
        foreach ($matches as $match) {

            list($before) = str_split($content, $match['name'][1]);
            $line_number   = strlen($before) - strlen(str_replace("\n", '', $before)) + 1;

            $subject = '';
            if (isset($match['subject'][0])) {
                $subject = stripslashes( preg_replace($extension_after_pattern, $extension_after_replace, var_export($match['subject'][0], true)) );
            }
            $params = '';
            if (isset($match['params'][0])) {
                $params = stripslashes( preg_replace($extension_after_pattern, $extension_after_replace, var_export($match['params'][0], true)) );
            }
            $extension                      = array();
            $extension['extension_point']   = $match['name'][0];
            $extension['subject']           = $subject;
            $extension['params']            = $params;
            $extension['readonly']          = isset($match['readonly'][0]) ? $match['readonly'][0] : '';
            $extension['filepath']          = str_replace(realpath($REX['HTDOCS_PATH']), '', $file);
            $extension['line_number']       = $line_number;

            $extensions[] = $extension;
        }
    }
}


sort($extensions);

$counter = 0;
$toJson = array();
$extensions_save = array();

$data = array();
foreach ($extensions as $extension) {
    $counter++;

    $json = array();
    $json['name']         = $extension['extension_point'];
    $json['subject']      = $extension['subject'];
    $json['params']       = $extension['params'];
    $json['readonly']     = $extension['readonly'];
    $json['filepath']     = $extension['filepath'];
    $json['line_number']  = $extension['line_number'];
    $toJson[] = $json;

    $class = isset($extensions_save[$extension['extension_point']]) ? ' style="background: #f90;"' : '';
    $extensions_save[$extension['extension_point']] = '';

    $url = 'https://github.com/redaxo/redaxo' . $REX['VERSION'] . '/blob/' . $REX['VERSION'] . '.' .  $REX['SUBVERSION'] . '.' . $REX['MINORVERSION'] . $extension['filepath'] . '#L' . $extension['line_number'];
    $output = array(
        $counter,
        '<a href="' . $url .'" onclick="window.open(this.href); return false">' . $extension['extension_point'] . '</a>',
        $extension['line_number'],
        '<pre><code>' . $extension['subject'] . '</code></pre>',
        '<pre><code>' . $extension['params'] . '</code></pre>',
        $extension['readonly'],
        $extension['filepath'],
    );
    $data[] = '<td>' . implode('</td><td>', $output) . '</td>';

}


$info = array();
$warning = array();

$file = SHERLOCK_CACHE_FILE_ADDONS_CHECKED;
$file_msg = str_replace(rex_path::src(), '', $file);
if (!rex_file::putCache($file, $addons_checked)) {
    $warning[] = rex_i18n::msg('b_cache_file_was_not_written', $file_msg);
} else {
    $info[] = rex_i18n::msg('b_cache_file_was_written', $file_msg);
}

$file = SHERLOCK_CACHE_FILE_EXTENSION_POINTS;
$file_msg = str_replace(rex_path::src(), '', $file);
if (!rex_file::putCache($file, $toJson)) {
    $warning[] = rex_i18n::msg('b_cache_file_was_not_written', $file_msg);
} else {
    $info[] = rex_i18n::msg('b_cache_file_was_written', $file_msg);
}

$info[] = rex_i18n::msg('b_parsed_files', count($files));
$info[] = rex_i18n::msg('b_found_extension_points', count($extensions));

if (count($warning) > 0) {
    echo rex_warning_block('<ul><li>' . implode('</li><li>', $warning) . '</li></ul>');
}
if (count($info) > 0) {
    echo rex_info_block('<ul><li>' . implode('</li><li>', $info) . '</li></ul>');
}



echo '<table class="rex-table">';
echo '<tr><th>&nbsp;</th><th>Extension Point</th><th>Zeile</th><th>Subject</th><th>Params</th><th>readonly</th><th>Datei</th></tr>';
echo '<tr>' . implode('</tr><tr>', $data) . '</tr>';
echo '</table>';
