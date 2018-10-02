<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Fields\Field;
use Illuminate\Support\Collection;

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
