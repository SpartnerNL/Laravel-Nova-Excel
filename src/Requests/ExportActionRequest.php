<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Resource;

interface ExportActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery();

    /**
     * @param Resource $resource
     *
     * @return array
     */
    public function indexFields(Resource $resource): array;

    /**
     * @param Resource $resource
     *
     * @return Collection|Field[]
     */
    public function resourceFields(Resource $resource): Collection;

    /**
     * @param string      $attribute
     * @param string|null $default
     *
     * @return string
     */
    public function findHeading(string $attribute, string $default = null): string;
}
