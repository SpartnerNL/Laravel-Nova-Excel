<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Http\Requests\ActionRequest;

class ExportResourceActionRequest extends ActionRequest implements ExportActionRequest
{
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
     * @return array
     */
    public function indexFields(): array
    {
        return $this->newResource()->indexFields($this)->map->attribute->all();
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function indexHeadings(array $attributes = null): array
    {
        $fields = $this->newResource()->indexFields($this);

        if (is_array($attributes)) {
            $fields = $fields->only($attributes);
        }

        return $fields->map->name->all();
    }

    /**
     * @param string      $attribute
     * @param string|null $default
     *
     * @return string
     */
    public function findHeading(string $attribute, string $default = null): string
    {
        $field = $this
            ->newResource()
            ->indexFields($this)
            ->firstWhere('attribute', $attribute);

        if (null === $field) {
            return $default;
        }

        return $field->name;
    }
}
