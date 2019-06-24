<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Fields\Field;
use Throwable;
use Laravel\Nova\Resource;

trait WithRowValidation
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return collect($this->import->mapping)->filter()->flatMap(function (string $attribute) {
            return $this->rulesFor($attribute);
        })->mapWithKeys(function ($rules, $attribute) {
            return [$this->attributeToColumn($attribute) => $rules];
        })->all();
    }

    /**
     * @param string $attribute
     *
     * @return array
     */
    protected function rulesFor(string $attribute)
    {
        try {
            return $this->resource()::creationRulesFor($this->request, $attribute);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return collect($this->import->mapping)->filter()->mapWithKeys(function (string $attribute, int $index) {
            return [
                $index => $this->attributeToField($attribute),
            ];
        })->all();
    }

    /**
     * @return Resource
     */
    abstract protected function resource();
}