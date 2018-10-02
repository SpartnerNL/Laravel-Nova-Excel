<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\Collection;

trait WithIndexFields
{
    /**
     * @param resource $resource
     *
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
     * @param resource $resource
     *
     * @return Collection|Field[]
     */
    abstract public function resourceFields(Resource $resource): Collection;
}
