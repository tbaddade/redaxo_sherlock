<?php

namespace Sherlock;

class Sherlock
{
    const LIKE_MODE = true;

    private $searchers = [];

    public static function create()
    {
        return new self();
    }

    public function addSearch(Search $instance)
    {
        $this->searchers[] = $instance;
        return $this;
    }


    public function hasSearchers()
    {
        return count($this->searchers) > 0;
    }


    /**
     * @param $filter string
     *
     * @return null|string
    */
    public function search($filter = null)
    {
        if (!$this->hasSearchers()) {
            return null;
        }

        $searchFilter = SearchFilter::create();
        if ($filter) {
            $searchFilter->setCurrent($filter);
        } else {
            $searchFilter->handleRequest();
        }

        if (!$searchFilter->getCurrent()) {
            return null;
        }

        $searchQuery = new SearchQuery($searchFilter);

        if (!$searchQuery) {
            return null;
        }

        $results = [];
        foreach ($this->searchers as $searcher) {
            $resultSet = $searcher->search($searchQuery);
            if (!$resultSet) {
                continue;
            }
            $results[] = $resultSet;
        }

        // Ergebnis rendern
        $renderedResults = [];
        foreach ($results as $resultSet) {
            $renderedResults[] = $resultSet->render();
        }

        return implode('', $renderedResults);
    }

    /**
     * Returns if Sherlock is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return true;
    }
}
