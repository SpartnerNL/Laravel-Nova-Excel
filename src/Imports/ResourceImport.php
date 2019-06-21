<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
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

        $mapped['password'] = 'test';

        return $this->import->getModelInstance()->newInstance()->forceFill($mapped->toArray());
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
