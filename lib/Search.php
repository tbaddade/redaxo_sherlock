<?php

namespace Sherlock;

abstract class Search
{
    /**
     * Execute the command for the given Command.
     *
     * @param $query SearchQuery
     *
     * @return ResultSet
     */
    abstract public function search(SearchQuery $query);

    abstract public function getTable();

    abstract public function getFields();

}
