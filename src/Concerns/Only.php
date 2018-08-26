<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait Only
{
    /**
     * @var array
     */
    protected $only = [];

    /**
     * @param array|mixed $columns
     *
     * @return $this
     */
    public function only($columns)
    {
        $this->only = \is_array($columns) ? $columns : \func_get_args();

        return $this;
    }

    /**
     * @return array
     */
    public function getOnly(): array
    {
        return $this->only;
    }
}
