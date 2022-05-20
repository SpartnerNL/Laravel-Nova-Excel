<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\LensActionRequest;
use Laravel\Nova\Resource;

class ExportLensActionRequest extends LensActionRequest implements ExportActionRequest
{
    use WithIndexFields;
    use WithHeadingFinder;

    /**
     * @var \Laravel\Nova\Resource
     */
    protected $resourceInstance;

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery()
    {
        return $this->toQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $groups = $query->getQuery()->groups;

            is_array($groups) && 1 === count($groups)
                ? $query->whereIn($groups[0], explode(',', $this->resources))
                : $query->whereKey(explode(',', $this->resources));
        });
    }

    /**
     * @param  \Laravel\Nova\Resource  $resource
     * @return Collection|Field[]
     */
    public function resourceFields(Resource $resource): Collection
    {
        $this->resourceInstance = $resource;

        $lens           = $this->lens();
        $lens->resource = $resource->model();

        return $lens->resolveFields($this)
            ->filterForIndex($this, $lens->resource)
            ->withoutListableFields();
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

    /**
     * Determine if the request is for all matching resources.
     *
     * @return bool
     */
    public function forAllMatchingResources()
    {
        return $this->resources === 'all';
    }
}
