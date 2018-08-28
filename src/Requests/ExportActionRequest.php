<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Http\Requests\ActionRequest;

class ExportActionRequest extends ActionRequest
{
    /**
     * @param bool  $onlyIndexFields
     * @param array $only
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery(bool $onlyIndexFields = false, array $only = [])
    {
        return $this->toSelectedResourceQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        })->when($onlyIndexFields, function ($query) {
            return $query->select($this->newResource()->indexFields($this)->map->attribute->unique()->all());
        })->when(\count($only) > 0, function ($query) use ($only) {
            $query->select($only);
        });
    }
}
