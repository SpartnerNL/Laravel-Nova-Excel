<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait Except
{
    /**
     * @var array
     */
    protected $except = [];

    /**
     * @param array|mixed $columns
     *
     * @return $this
     */
    public function except($columns)
    {
        $this->except = \is_array($columns) ? $columns : \func_get_args();

        return $this;
    }

    /**
     * @return array
     */
    public function getExcept(): array
    {
        return $this->except;
    }
}
