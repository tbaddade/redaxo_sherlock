<?php

namespace Sherlock;

class Result
{
    private $text;
    private $title;
    private $url;
    private $raw;

    /**
     * @param string $value
     */
    public function setRaw($value)
    {
        $this->raw = $value;
    }

    /**
     * Returns the raw
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @param string $value
     */
    public function setText($value)
    {
        $this->text = $value;
    }

    /**
     * Returns the text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @param string $value
     */
    public function setTitle($value)
    {
        $this->title= $value;
    }

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $value
     */
    public function setUrl($value)
    {
        $this->url = $value;
    }

    /**
     * Returns the url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
