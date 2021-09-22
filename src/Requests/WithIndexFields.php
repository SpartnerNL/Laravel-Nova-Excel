<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Resource;

trait WithIndexFields
{
    /**
     * @param  \Laravel\Nova\Resource  $resource
     * @return array
     */
    public function indexFields(Resource $resource): array
    {
        return $this->resourceFields($resource)->map(function (Field $field) {
            if (!$field->computed()) {
                return $field->attribute;
            }

            return $field->name;
        })->unique()->all();
    }

    /**
     * @param  \Laravel\Nova\Resource  $resource
     * @return Collection|Field[]
     */
    abstract public function resourceFields(Resource $resource): Collection;
}
