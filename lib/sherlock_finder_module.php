<?php

class sherlock_finder_module
{
    private $objects;

    public function __construct()
    {
        $this->objects = self::buildObjects();
    }

    protected function buildObjects()
    {
        $query_where = '';

        $query = '
            SELECT          article.id          AS article_id,
                            article.re_id       AS category_id,
                            article.clang       AS clang_id,
                            article.name        AS article_name,
                            article.status      AS article_status,
                            slice.id            AS slice_id,
                            clang.name          AS clang_name,
                            module.id           AS module_id,
                            module.name         AS module_name,
                            CONCAT( article.id, "|", module.id ) AS article_module

            FROM            ' . rex::getTable('article_slice') . '   AS slice

                LEFT JOIN   ' . rex::getTable('module') . '          AS module
                    ON      slice.modultyp_id = module.id

                LEFT JOIN   ' . rex::getTable('article') . '         AS article
                    ON      slice.article_id  = article.id

                LEFT JOIN   ' . rex::getTable('clang') . '           AS clang
                    ON      slice.clang       = clang.id


            WHERE       article.clang       = slice.clang
            ' . $query_where . '
            GROUP BY    article_module
            ORDER BY    module_name, article_name
        ';

        $s = rex_sql::factory();
        //$s->debugsql = true;
        $s->setQuery($query);
        return json_decode(json_encode($s->getArray()), false);


    }


    public function getFirstFinderColumn()
    {
        return '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'module'))) . '">' . rex_i18n::msg('b_modules') . '</span>';
    }


    public function getFinderColumn()
    {
        $module = rex_request('module', 'string');

        $output = '';
        if ($module) {
            // Modul vorhanden
            $name     = '';
            $articles = '';
            foreach ($this->objects as $object) {
                if ($module == $object->module_id) {

                    if ($name == '') {
                        $name = '<dt>' . $object->module_name . '</dt>
                                 <dd><a class="sherlock-edit" href="' . rex_url::backendPage('module', array('function' => 'edit', 'modul_id' => $object->module_id)) . '">' . rex_i18n::msg('b_module') . ' ' . rex_i18n::msg('b_edit') . '</a></dd>';
                    }

                    $class = $object->article_status ? 'rex-online' : 'rex-offline';
                    $articles .= '<dd><a class="' . $class . '" href="' . rex_url::backendPage('content', array('article_id' => $object->article_id, 'category_id' => $object->category_id, 'mode' => 'edit', 'clang' => $object->clang_id)) . '">' . $object->article_name . '</a></dd>';
                }
            }
            $output = '<dl class="sherlock-view">
                        ' . $name . '
                        <dt>' . rex_i18n::msg('b_articles') . '</dt>
                        ' . $articles . '
                       </dl>';
        } else {
            $module_list = array();
            foreach ($this->objects as $object) {
                $module_list[$object->module_id] = $object->module_name;
            }
            foreach ($module_list as $id => $name) {
                $output .= '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'module', 'module' => $id))) . '">' . $name . '</span>';
            }
        }

        rex_response::sendContent($output);
        exit();
    }

}
