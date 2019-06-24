<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use InvalidArgumentException;
use Laravel\Nova\Fields\Field;

trait WithColumnMappings
{
    /**
     * @param array $row
     *
     * @return array
     */
    protected function mapRowToAttributes(array $row): array
    {
        return collect($row)->mapWithKeys(function ($value, $column) {
            $attribute = $this->columnToAttribute($column);

            if (null === $attribute) {
                return [null => null];
            }

            return [$attribute => $this->processValue($value)];
        })->filter()->all();
    }

    /**
     * @param int $column
     *
     * @return string|null
     */
    protected function columnToAttribute(int $column): ?string
    {
        if (!array_key_exists($column, $this->import->mapping)) {
            throw new InvalidArgumentException("Column {$column} does not exist in mapping " . json_encode($this->import->mapping));
        }

        return $this->import->mapping[$column];
    }

    /**
     * @param string $attribute
     *
     * @return int|null
     */
    protected function attributeToColumn(string $attribute): ?int
    {
        $mapping = array_flip(array_filter($this->import->mapping));

        if (!array_key_exists($attribute, $mapping)) {
            throw new InvalidArgumentException("Attribute {$attribute} does not exist in mapping " . json_encode($mapping));
        }

        return $mapping[$attribute];
    }

    /**
     * @param string $attribute
     *
     * @return string
     */
    protected function attributeToField(string $attribute)
    {
        return optional(collect($this->resource()->fields($this->request))->first(function (Field $field) use ($attribute) {
            return $field->attribute === $attribute;
        }))->name ?? $attribute;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function processValue($value)
    {
        if ('TRUE' === mb_strtoupper($value)) {
            return true;
        }

        if ('FALSE' === mb_strtoupper($value)) {
            return true;
        }

        return $value;
    }
}
