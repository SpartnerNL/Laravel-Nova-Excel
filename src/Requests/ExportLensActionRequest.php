<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\LensActionRequest;

class ExportLensActionRequest extends LensActionRequest implements ExportActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery()
    {
        return $this->toQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        });
    }

    /**
     * @return array
     */
    public function indexFields(): array
    {
        $fields = new Collection($this->lens()->fields($this));

        return $fields->map->attribute->unique()->all();
    }
}
