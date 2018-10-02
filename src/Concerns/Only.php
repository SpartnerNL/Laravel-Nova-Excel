<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\LaravelNovaExcel\Requests\ExportActionRequest;

trait Only
{
    /**
     * @var array|null
     */
    protected $only;

    /**
     * @var bool
     */
    protected $onlyIndexFields = true;

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
     * @return array|null
     */
    public function getOnly()
    {
        return $this->only;
    }

    /**
     * @param ExportActionRequest|ActionRequest $request
     */
    protected function handleOnly(ExportActionRequest $request)
    {
        // If not a specific only array, and user wants only index fields,
        // fill the only with the index fields.
        if ($this->onlyIndexFields && (!is_array($this->only) || count($this->only) === 0)) {
            $this->only = $request->indexFields($request->newResource());
        }
    }
}
