<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait Except
{
    /**
     * @var array|null
     */
    protected $except;

    /**
     * @param  array|mixed  $columns
     * @return $this
     */
    public function except($columns)
    {
        $this->except = \is_array($columns) ? $columns : \func_get_args();

        return $this;
    }

    /**
     * @return array|null
     */
    public function getExcept()
    {
        return $this->except;
    }
}
