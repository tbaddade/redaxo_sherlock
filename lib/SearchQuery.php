<?php

namespace Sherlock;

class SearchQuery
{
    protected $queryParts;
    protected $searchParts;

    public function __construct(SearchFilter $filter)
    {
        $this->queryParts = [
            'search' => [],
        ];

        $this->searchParts = [
            'search' => preg_split('/".*?"(*SKIP)(*FAIL)|\s+/', $filter->getCurrent()),
        ];

        if (Sherlock::LIKE_MODE) {
            $this->queryParts = $this->convertLike();
        } else {
            $this->queryParts = $this->convertAgainst();
        }

        $this->queryParts = array_filter($this->queryParts);
        if (!$this->queryParts) {
            return null;
        }
    }

    protected function convertLike()
    {
        $queryParts = $this->queryParts;

        foreach ($this->searchParts as $field => $parts) {
            foreach ($parts as $part) {
                if (!$part) {
                    continue;
                }
                $part = str_replace(['"', '+', '-', '~', '(', ')', '<', '>'], ' ', $part);
                $part = trim($part);
                $queryParts[$field][] = '%'.$part.'%';
            }
        }

        return $queryParts;
    }

    protected function convertAgainst()
    {
        $queryParts = $this->queryParts;

        foreach ($this->searchParts as $field => $parts) {
            foreach ($parts as $part) {
                if (!$part) {
                    continue;
                }
                $op = '-' === $part[0] ? '-' : '+';
                $part = ltrim($part, '+- ');
                if (!$part) {
                    continue;
                }
                $quoted = '"' === $part[0] && '"' === substr($part, -1);
                if ($quoted) {
                    $part = substr($part, 1, -1);
                }
                $part = str_replace(['"', '+', '-', '~', '(', ')', '<', '>'], ' ', $part);
                if (mb_strlen($part) <= 2) {
                    continue;
                }
                if ($quoted || preg_match('/[ @*]/', $part)) {
                    $part = '"'.$part.'"';
                } else {
                    $part .= '*';
                }
                $queryParts[$field][] = $op.$part;
            }
        }

        return $queryParts;
    }

    public function getWhere(array $fields)
    {
        if (Sherlock::LIKE_MODE) {
            return $this->getWhereLike($fields);
        }

        return $this->getWhereAgainst($fields);
    }

    private function getWhereLike($fields)
    {
        foreach ($this->queryParts as $field => $parts) {
            if (!$parts) {
                continue;
            }

            $where = [];
            $whereParams = [];
            foreach ($fields as $f) {
                $whereParts = [];
                foreach ($parts as $part) {
                    $whereParts[] = sprintf('(`%s` LIKE ?)', $f);
                    $whereParams[] = $part;
                }
                $where[] = '(' . implode(' AND ', $whereParts) . ')';
            }

            // ist aktuell nur ein $field in $this->queryParts => 'search'
            return ['where' => '('.implode(' OR ', $where).')', 'params' => $whereParams];
        }
        return null;
    }

    private function getWhereAgainst($fields)
    {
        foreach ($this->queryParts as $field => $parts) {
            if (!$parts) {
                continue;
            }

            // ist aktuell nur ein $field in $this->queryParts => 'search'
            // später sollen Parameter wie "von:" mit übergeben werden
            return ['where' => sprintf('MATCH(%s) AGAINST (? IN BOOLEAN MODE)', implode(', ', $fields)), 'params' => [implode(' ', $parts)]];
        }
        return null;
    }
}
