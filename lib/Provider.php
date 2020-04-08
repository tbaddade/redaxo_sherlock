<?php

namespace Sherlock;


abstract class Provider
{
    /**
     * Register the search provider.
     *
     * @return Workflow|array
     */
    abstract function register();
}
