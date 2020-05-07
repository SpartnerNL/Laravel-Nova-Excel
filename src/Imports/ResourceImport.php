<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\LaravelNovaExcel\Concerns\BelongsToAction;
use Maatwebsite\LaravelNovaExcel\Concerns\KeepsTrackOfImport;
use Maatwebsite\LaravelNovaExcel\Concerns\WithColumnMappings;
use Maatwebsite\LaravelNovaExcel\Concerns\WithRowValidation;
use Maatwebsite\LaravelNovaExcel\Models\Import;

class ResourceImport implements ToModel, WithStartRow, WithChunkReading, WithValidation
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
     * @var callable
     */
    protected $onModelCreatedCallback;

    /**
     * @var callable
     */
    protected $onModelQueryCallback;

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

        $modelInstance = $this->import
            ->getModelInstance();

        $model = null;
        $action = (object) $this->request->input('action');
        $meta = (object)  $this->request->input('meta', []);
        if (isset($action) && $action->uriKey === 're-import-excel') {
            $matchOn = $this->request->input('matchOn');
            $match = array_filter($attributes, function ($key) use ($matchOn) {
                return in_array($key, $matchOn);
            }, ARRAY_FILTER_USE_KEY);
            $model = $modelInstance
                ->where($match)
                ->when(is_callable($this->onModelQueryCallback), function ($query) use ($meta) {
                    return ($this->onModelQueryCallback)($query, $meta);
                })
                ->firstOr(function () use ($modelInstance) {
                    return $modelInstance
                        ->newInstance();
                });
        } else {
            $model = $modelInstance
                ->newInstance();
        }

        $model = $model->forceFill($attributes);

        if (is_callable($this->onModelCreatedCallback)) $model = ($this->onModelCreatedCallback)($model, $meta);

        if ($this->shouldKeepTrackOfImport($model)) {
            $this->associateImport($model);
        }

        return $model;
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
     * @return resource
     */
    protected function resource()
    {
        return $this->import->getResourceInstance();
    }

    public function onModelCreated($callback)
    {
        $this->onModelCreatedCallback = $callback;

        return $this;
    }

    public function onModelQuery($callback)
    {
        $this->onModelQueryCallback = $callback;

        return $this;
    }
}
