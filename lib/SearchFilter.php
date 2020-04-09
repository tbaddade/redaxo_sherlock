<?php

namespace Sherlock;

class SearchFilter
{
    private $current;

    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public static function create(string $key = 'q'): self
    {
        return new static($key);
    }

    public function setCurrent(?string $current): self
    {
        $this->current = $current;
        return $this;
    }

    public function getCurrent(): ?string
    {
        return $this->current;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function handleRequest(): self
    {
        $current = \rex_request($this->key, 'string', null);
        return $this->setCurrent($current ?: null);
    }

    public function handleQuery($query): self
    {
        if (null === $this->current) {
            return $this;
        }
        /* @noinspection PhpUndefinedMethodInspection */
        $query->search($this->current);
        return $this;
    }

}
