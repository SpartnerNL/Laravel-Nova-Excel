<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\LensActionRequest;

class ExportLensActionRequest extends LensActionRequest implements ExportActionRequest
{
    use WithIndexFields;
    use WithHeadingFinder;

    /**
     * @var resource
     */
    protected $resourceInstance;

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
     * @param resource $resource
     *
     * @return Collection|Field[]
     */
    public function resourceFields(Resource $resource): Collection
    {
        $this->resourceInstance = $resource;

        $lens           = $this->lens();
        $lens->resource = $resource->model();

        return $lens->resolveFields($this);
    }

    /**
     * Get all of the possible lenses for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    public function availableLenses()
    {
        if (!$this->resourceInstance) {
            $this->resourceInstance = $this->newResource();
        }

        return $this->resourceInstance->availableLenses($this);
    }
}
