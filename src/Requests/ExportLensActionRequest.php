<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\LensActionRequest;

class ExportLensActionRequest extends LensActionRequest implements ExportActionRequest
{
    use WithIndexFields;
    use WithHeadingFinder;

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
     * @return Collection|Field[]
     */
    public function resourceFields()
    {
        return new Collection($this->lens()->fields($this));
    }
}
