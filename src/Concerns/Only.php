<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\LaravelNovaExcel\Requests\ExportActionRequest;

trait Only
{
    /**
     * @var array
     */
    protected $only = [];

    /**
     * @var bool
     */
    protected $onlyIndexFields = true;

    /**
     * @param  array|mixed  $columns
     * @return $this
     */
    public function only($columns)
    {
        $this->only = \is_array($columns) ? $columns : \func_get_args();

        return $this;
    }

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

    /**
     * @return array
     */
    public function getOnly()
    {
        return $this->only;
    }

    /**
     * @param  ExportActionRequest|ActionRequest  $request
     */
    protected function handleOnly(ExportActionRequest $request)
    {
        // If not a specific only array, and user wants only index fields,
        // fill the only with the index fields.
        $indexFields = $request->indexFields($request->newResource());

        if ($this->onlyIndexFields && count($this->only) === 0) {
            $this->only = $indexFields;
        } elseif (!$this->onlyIndexFields) {
            $modelAttributes = optional($request->toQuery()->first())->attributesToArray() ?: [];

            $this->only = array_merge(array_keys($modelAttributes), $indexFields);
        }
    }
}
