<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\ActionRequest;

class ExportResourceActionRequest extends ActionRequest implements ExportActionRequest
{
    use WithIndexFields;
    use WithHeadingFinder;

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
     * @param \Laravel\Nova\Resource $resource
     *
     * @return Collection|Field[]
     */
    public function resourceFields(Resource $resource): Collection
    {
        return $resource->indexFields($this);
    }
}
