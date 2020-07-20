<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
     * @var object
     */
    protected $meta;

    /**
     * @var array
     */
    protected $matchOn;

    /**
     * @var object
     */
    protected $action;

    /**
     * @var callable
     */
    protected $onModelCreatedCallback;

    /**
     * @var callable
     */
    protected $onRowValidationCallback;

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

        // Instead of setting these on each loop, instead set them once
        // also, this way we will loose the requirement of the $request variable
        // which allows us to serialize the import
        $this->meta = (object) $this->request->input('meta');
        $this->matchOn = $this->request->input('matchOn');
        $this->action = (object) $this->request->input('action');

        Log::info('Intialized import', [
            'meta' => $this->meta,
            'matchOn' => $this->matchOn,
            'action' => $this->action
        ]);
    }

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        $attributes = $this->map($row);

        if (!isset($attributes) || count(array_filter($attributes)) === 0 || !$this->onRowValidationExecute($attributes)) {
            return null;
        }

        $modelInstance = $this->import
            ->getModelInstance();

        $model = null;
        // $action = (object) $this->request->input('action');
        // $meta = (object)  $this->request->input('meta', []);
        if (isset($this->action) && $this->action->uriKey === 're-import-excel') {
            // $matchOn = $this->request->input('matchOn');
            $match = array_filter($attributes, function ($key) {
                return in_array($key, $this->matchOn);
            }, ARRAY_FILTER_USE_KEY);

            $model = empty($match)
                ? $modelInstance->newInstance()
                : $this->onModelQueryExecute(
                    $modelInstance
                        ->withTrashed()
                        ->where($match),
                    $this->meta
                )
                ->firstOr(function () use ($modelInstance) {
                    return $modelInstance
                        ->newInstance();
                });
        } else {
            $model = $modelInstance
                ->newInstance();
        }

        Log::info('Import testing:', [
            'match_empty' => empty($match),
            'model_type' => get_class($model),
            'model_methods' => get_class_methods($model),
            'method_exists:withTrashed' => method_exists($modelInstance, 'withTrashed'),
            'method_exists:trashed' => method_exists($model, 'trashed'),
            'model_trashed' => method_exists($model, 'trashed') ? $model->trashed() : null
        ]);

        $model = $model->forceFill($attributes);

        if (method_exists($model, 'trashed') && $model->trashed()) $model->deleted_at = null;

        $model = $this->onModelCreatedExecute($model, $this->meta);

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

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function onModelCreated($callback): self
    {
        $this->onModelCreatedCallback = $callback;

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function onRowValidation($callback): self
    {
        $this->onRowValidationCallback = $callback;

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function onModelQuery($callback): self
    {
        $this->onModelQueryCallback = $callback;

        return $this;
    }

    /**
     * @param Model|Model[]|null $model
     * @param object $meta
     *
     * @return Model
     */
    public function onModelCreatedExecute($model, object $meta): Model
    {
        return is_callable($this->onModelCreatedCallback) ? ($this->onModelCreatedCallback)($model, $meta) : $model;
    }

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function onRowValidationExecute(array $attributes): bool
    {
        return (is_callable($this->onRowValidationCallback) && !($this->onRowValidationCallback)($attributes));
    }

    /**
     * @param Builder $query
     * @param object $meta
     *
     * @return Builder
     */
    public function onModelQueryExecute(Builder $query, object $meta): Builder
    {
        return is_callable($this->onModelQueryCallback) ? ($this->onModelQueryCallback)($query, $meta) : $query;
    }
}
