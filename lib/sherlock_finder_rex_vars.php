<?php

class sherlock_finder_rex_vars
{
    private $objects;

    public function __construct()
    {
        $this->objects = self::buildObjects();
    }

    protected function buildObjects()
    {
        $global_params = array('id', 'prefix', 'suffix', 'ifempty', 'instead', 'callback');

        $array = array();
        /*
        $a['name']   = 'REX_VAR';
        $a['params'] = // Params/Args
        $a['where']  = // Wo kann die Var genutzt werden
        */

        $a = array();
        $a['name']   = 'REX_ARTICLE';
        $a['params'] = array_merge($global_params, array('clang', 'ctype', 'field'));
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_CATEGORY';
        $a['params'] = array_merge($global_params, array('clang', 'field'));
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_CONFIG';
        $a['params'] = array_merge($global_params, array('field'));
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_LINK_ID';
        $a['params'] = array_merge(array('1 - 10'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_LINK';
        $a['params'] = array_merge(array('1 - 10'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_LINK_BUTTON';
        $a['params'] = array('1 - 10', 'id', 'category');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_LINKLIST';
        $a['params'] = array_merge(array('1 - 10'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_LINKLIST_BUTTON';
        $a['params'] = array('1 - 10', 'id', 'category');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_MEDIA';
        $a['params'] = array_merge(array('1 - 10'), $global_params, array('mimetype'));
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_MEDIA_BUTTON';
        $a['params'] = array('1 - 10', 'id', 'category', 'preview', 'types');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_MEDIALIST';
        $a['params'] = array_merge(array('1 - 10'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_MEDIALIST_BUTTON';
        $a['params'] = array('1 - 10', 'id', 'category', 'preview', 'types');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_TEMPLATE';
        $a['params'] = $global_params;
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_VALUE';
        $a['params'] = array_merge(array('1 - 20'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_IS_VALUE';
        $a['params'] = array_merge(array('1 - 20'), $global_params);
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_HTML';
        $a['params'] = $global_params;
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_HTML_VALUE';
        $a['params'] = $global_params;
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_PHP';
        $a['params'] = $global_params;
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_PHP_VALUE';
        $a['params'] = $global_params;
        $array[] = $a;



        $a = array();
        $a['name']   = 'REX_ARTICLE_ID';
        $a['params'] = array();
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_CATEGORY_ID';
        $a['params'] = array();
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_CLANG_ID';
        $a['params'] = array();
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_CTYPE_ID';
        $a['params'] = array();
        //$a['where']  = array('module_input', 'module_output');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_MODULE_ID';
        $a['params'] = array();
        //$a['where']  = array('module_input', 'module_output');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_SLICE_ID';
        $a['params'] = array();
        //$a['where']  = array('module_input', 'module_output');
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_TEMPLATE_ID';
        $a['params'] = array();
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_USER_ID';
        $a['params'] = array();
        $array[] = $a;

        $a = array();
        $a['name']   = 'REX_USER_LOGIN';
        $a['params'] = array();
        $array[] = $a;


        sort($array);
        return json_decode(json_encode($array), FALSE);
    }


    public function getFirstFinderColumn()
    {
        return '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'rex_vars'))) . '">' . rex_i18n::msg('b_rex_vars') . '</span>';
    }


    public function getFinderColumn()
    {
        $rex_var = rex_request('rex_var', 'string');

        $output = '';
        if ($rex_var) {
            // REX_VAR vorhanden
            foreach ($this->objects as $object) {
                if ($rex_var == $object->name) {

                    $params = '';
                    if (isset($object->params) && count($object->params) > 0) {
                        $params .= '<dt>' . rex_i18n::msg('b_params'). '</dt>';
                        $params .= '<dd>' . implode('</dd><dd>', $object->params) . '</dd>';
                    }

                    $output = '
                    <dl class="sherlock-view">
                        <dt>' . rex_i18n::msg('b_name'). '</dt>
                        <dd>' . $object->name . '</dd>
                        ' . $params . '
                    </dl>';

                    break;
                }
            }
        } else {
            foreach ($this->objects as $object) {
                $output .= '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'rex_vars', 'rex_var' => $object->name))) . '">' . $object->name . '</span>';
            }
        }

        rex_response::sendContent($output);
        exit();
    }

}
