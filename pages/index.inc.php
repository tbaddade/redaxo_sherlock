<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$page    = rex_request('page', 'string');
$func    = rex_request('func', 'string');

$subpage = rex_request('subpage', 'string');
$subpage = $subpage != '' ? $subpage : 'parse';

$subpages = array();
if ($REX['USER']->isAdmin()) {
    $subpages[] = array('', rex_i18n::msg('b_parse_files'));
}


require $REX['INCLUDE_PATH'] . '/layout/top.php';

switch ($subpage) {
    case 'parse':
        // Parse
        rex_title('<span class="sherlock">' . rex_i18n::msg('b_sherlock') . '</span>', $subpages);
        include rex_path::addon('sherlock', 'pages/' . $subpage . '.php');
        break;
}
require $REX['INCLUDE_PATH'] . '/layout/bottom.php';

