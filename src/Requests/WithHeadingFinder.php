<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

trait WithHeadingFinder
{
    /**
     * @param string      $attribute
     * @param string|null $default
     *
     * @return string
     */
    public function findHeading(string $attribute, string $default = null): string
    {
        return $default;
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @return \Laravel\Nova\Resource
     */
    abstract public function newResource();
}
