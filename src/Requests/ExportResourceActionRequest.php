<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Http\Requests\ActionRequest;

class ExportResourceActionRequest extends ActionRequest implements ExportActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery()
    {
        return $this->toSelectedResourceQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        });
    }

    /**
     * @return array
     */
    public function indexFields(): array
    {
        return $this->newResource()->indexFields($this)->map->attribute->unique()->all();
    }
}
