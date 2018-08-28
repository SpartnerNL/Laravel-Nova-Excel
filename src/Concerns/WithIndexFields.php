<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait WithIndexFields
{
    /**
     * @var bool
     */
    protected $onlyIndexFields = false;

    /**
     * @return $this
     */
    public function onlyIndexFields()
    {
        $this->onlyIndexFields = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function allFields()
    {
        $this->onlyIndexFields = false;

        return $this;
    }
}
