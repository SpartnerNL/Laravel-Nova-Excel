<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\LaravelNovaExcel\BelongsToImport;
use Maatwebsite\LaravelNovaExcel\Models\Import;

class ResourceImport implements ToModel, WithStartRow
{
    /**
     * @var Import
     */
    private $import;

    /**
     * @param Import $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        $mapped = collect($row)->mapWithKeys(function ($column, $index) {
            return [$this->import->mapping[$index] => $column];
        });

        $model = $this->import->getModelInstance()->newInstance();
        $model->forceFill($mapped->toArray());

        if (in_array(BelongsToImport::class, trait_uses_recursive($model))) {
            $model->import()->associate($this->import);
        }

        return $model;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        // TODO: determine based on if the files has a heading row or not.
        return 2;
    }
}
