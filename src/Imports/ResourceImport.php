<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Laravel\Nova\Resource;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\LaravelNovaExcel\Models\Import;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\LaravelNovaExcel\Concerns\BelongsToAction;
use Maatwebsite\LaravelNovaExcel\Concerns\WithRowValidation;
use Maatwebsite\LaravelNovaExcel\Concerns\KeepsTrackOfImport;
use Maatwebsite\LaravelNovaExcel\Concerns\WithColumnMappings;

class ResourceImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithValidation
{
    use BelongsToAction;
    use WithRowValidation;
    use KeepsTrackOfImport;
    use WithColumnMappings;

    /**
     * @var Import
     */
    protected $import;

    /**
     * @var NovaRequest
     */
    protected $request;

    /**
     * @param Import      $import
     * @param NovaRequest $request
     */
    public function __construct(Import $import, NovaRequest $request)
    {
        $this->import  = $import;
        $this->request = $request;
    }

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        $attributes = $this->map($row);

        if (count(array_filter($attributes)) === 0) {
            return null;
        }

        $model = $this->import
            ->getModelInstance()
            ->newInstance()
            ->forceFill($attributes);

        if ($this->shouldKeepTrackOfImport($model)) {
            $this->associateImport($model);
        }

        return $model;
    }

    /**
     * @param array $row
     *
     * @return array|null
     */
    protected function map(array $row): ?array
    {
        return ($this->action()->getMap())(
            $this->mapRowToAttributes($row)
        );
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        if ($this->action()->usesHeadingRow()) {
            return 2;
        }

        return 1;
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 6000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 6000;
    }

    /**
     * @return resource
     */
    protected function resource()
    {
        return $this->import->getResourceInstance();
    }
}
