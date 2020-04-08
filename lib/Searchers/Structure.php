<?php

namespace Sherlock\Searcher;

use Sherlock\ResultSet;
use Sherlock\Result;
use Sherlock\Search;
use Sherlock\SearchQuery;

class Structure extends Search
{
    public function getTable()
    {
        return \rex::getTable('article_slice');
    }

    public function getFields()
    {
        return [
            'value1',
            'value2',
            'value3',
            'value4',
            'value5',
            'value6',
            'value7',
            'value8',
            'value9',
            'value10',
            'value11',
            'value12',
            'value13',
            'value14',
            'value15',
            'value16',
            'value17',
            'value18',
            'value19',
            'value20',
        ];
    }

    public function search(SearchQuery $query)
    {
        $fields = [];
        foreach ($this->getFields() as $field) {
            $fields[] = sprintf('`s`.`%s`', $field);
        }

        $where = $query->getWhere($fields);
        $where['params'] = array_merge([\rex_clang::getCurrentId()], $where['params']);

        $query = 'SELECT    s.article_id AS id,
                            s.clang_id,
                            s.ctype_id,
                            a.name as name, 
                            CONCAT(s.article_id, "|", s.clang_id) as bulldog
                FROM        '.\rex::getTable('article_slice').' AS s
                    LEFT JOIN
                            '.\rex::getTable('article').' AS a
                        ON  (s.article_id = a.id AND s.clang_id = a.clang_id)
                WHERE       s.clang_id = ? AND '.$where['where'].'
                GROUP BY    bulldog';

        $items = \rex_sql::factory()->getArray($query, $where['params']);

        if (count($items) < 1) {
            return null;
        }

        $resultSet = new ResultSet();
        $resultSet->setHeading('Artikel');

        foreach ($items as $item) {
            $article = \rex_article::get($item['id'], $item['clang_id']);

            if (!$article) {
                continue;
            }
            if (!$article->isOnline()) {
                continue;
            }
            if (!\rex_ycom_auth::checkPerm($article)) {
                continue;
            }

            $result = new Result();
            $result->setTitle($article->getName());
            $result->setUrl($article->getUrl());

            $resultSet->addResult($result);
        }

        return $resultSet;
    }
}
