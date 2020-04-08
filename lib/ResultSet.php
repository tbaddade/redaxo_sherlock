<?php

namespace Sherlock;

class ResultSet
{
    private $results = [];
    private $heading;


    /**
     * @param $value string
     */
    public function setHeading($value)
    {
        $this->heading = $value;
    }


    /**
     * @param $result Result
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;
    }

    /**
     * render all result entries
     */
    public function render()
    {
        if (count($this->results) < 1) {
            return null;
        }

        $fragment = new \rex_fragment();
        $fragment->setVar('title', $this->heading, false);
        $fragment->setVar('results', $this->results, false);
        return $fragment->parse('search-resultset.php');
    }
}
