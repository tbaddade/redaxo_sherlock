<?php

class sherlock_finder_templates
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
                            template.id         AS template_id,
                            template.name       AS template_name
            FROM            ' . rex::getTable('article') . '        AS article
                LEFT JOIN   ' . rex::getTable('template') . '       AS template
                    ON      article.template_id = template.id
                            ' . $query_where . '
            ORDER BY        template_name, article_name
        ';

        $s = rex_sql::factory();
        //$s->debugsql = true;
        $s->setQuery($query);
        return json_decode(json_encode($s->getArray()), FALSE);
    }


    public function getFirstFinderColumn()
    {
        return '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'templates'))) . '">' . rex_i18n::msg('b_templates') . '</span>';
    }


    public function getFinderColumn()
    {
        $template = rex_request('template', 'string');

        $output = '';
        if ($template) {
            // Template vorhanden
            $name     = '';
            $articles = '';
            foreach ($this->objects as $object) {
                if ($template == $object->template_id) {

                    if ($name == '') {
                        $name = '<dt>' . $object->template_name . '</dt>
                                 <dd><a class="sherlock-edit" href="' . rex_url::backendPage('template', array('function' => 'edit', 'template_id' => $object->template_id)) . '">' . rex_i18n::msg('b_template') . ' ' . rex_i18n::msg('b_edit') . '</a></dd>';
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
            $template_list = array();
            foreach ($this->objects as $object) {
                $template_list[$object->template_id] = $object->template_name;
            }
            foreach ($template_list as $id => $name) {
                $output .= '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'templates', 'template' => $id))) . '">' . $name . '</span>';
            }
        }

        rex_response::sendContent($output);
        exit();
    }

}
