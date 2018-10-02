<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;

trait WithIndexFields
{
    /**
     * @return array
     */
    public function indexFields(): array
    {
        return $this->resourceFields()->map->attribute->all();
    }

    /**
     * @return Collection|Field[]
     */
    abstract public function resourceFields();
}