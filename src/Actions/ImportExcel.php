<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Imports\ResourceImport;
use Maatwebsite\LaravelNovaExcel\Models\Import;

class ImportExcel extends Action
{
    /**
     * @var bool
     */
    protected $headingRow = true;

    /**
     * Indicates if this import action is available on the resource index view.
     *
     * @var bool
     */
    public $showImportActionOnIndex = true;

    /**
     * Indicates if this import action is available on the resource detail view.
     *
     * @var bool
     */
    public $showImportActionOnDetail = true;

    /**
     * @var int
     */
    protected $previewRows = 10;

    /**
     * @var callable
     */
    protected $usingImport;

    /**
     * @var callable|null
     */
    protected $map;

    /**
     * @var array
     */
    protected $additionalFields = [];

    /**
     * @var callable
     */
    public $afterCallback;

    /**
     * @var callable
     */
    protected $onModelCreatedCallback;

    /**
     * @var callable
     */
    protected $onModelQueryCallback;

    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->withoutActionEvents();
        $this->withoutConfirmation();
        $this->visible(false);
        $this->visibleImport(false);
        $this->showImportOnIndex();
        $this->availableForEntireResource(false);
        $this->usingImport(function ($import, $request) {
            return (new ResourceImport($import, $request))
                ->onModelCreated($this->onModelCreatedCallback)
                ->onModelQuery($this->onModelQueryCallback);
        });
    }

    /**
     * Set the action to not execute instantly.
     *
     * @return $this
     */
    public function withConfirmation()
    {
        $this->withoutConfirmation = false;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return ImportExcel
     */
    public static function make(string $name = null)
    {
        return new static($name);
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function usingImport(callable $callback)
    {
        $this->usingImport = $callback;

        return $this;
    }

    /**
     * @param Import      $import
     * @param NovaRequest $request
     *
     * @return ResourceImport
     */
    public function getImportObject(Import $import, NovaRequest $request)
    {
        return ($this->usingImport)($import, $request);
    }

    /**
     * @param bool $uses
     *
     * @return $this
     */
    public function usingHeadingRow(bool $uses)
    {
        $this->headingRow = $uses;

        return $this;
    }

    /**
     * @return bool
     */
    public function usesHeadingRow(): bool
    {
        return $this->headingRow;
    }

    /**
     * @param int $rows
     *
     * @return ImportExcel
     */
    public function previewRows(int $rows)
    {
        $this->previewRows = $rows;

        return $this;
    }

    /**
     * @return int
     */
    public function getPreviewRows(): int
    {
        return $this->previewRows;
    }

    /**
     * @param callable $callback
     *
     * @return ImportExcel
     */
    public function map(callable $callback)
    {
        $this->map = $callback;

        return $this;
    }

    /**
     * @return callable
     */
    public function getMap(): callable
    {
        return $this->map ?? function ($attributes) {
            return $attributes;
        };
    }

    /**
     * @return string
     */
    public function uriKey()
    {
        return 'import-excel';
    }

    public function visible(bool $value)
    {
        $this->showOnTableRow = $value;
        $this->showOnDetail = $value;
        $this->showOnIndex = $value;

        return $this;
    }

    public function visibleImport(bool $value)
    {
        $this->showImportActionOnDetail = $value;
        $this->showImportActionOnIndex = $value;

        return $this;
    }

    /**
     * @param \Laravel\Nova\Fields\Field $field
     *
     * @return void
     */
    public function addField(\Laravel\Nova\Fields\Field $field)
    {
        array_push($this->additionalFields, $field);
        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function after(callable $callback)
    {
        $this->afterCallback = $callback;

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function onModelCreated($callback)
    {
        $this->onModelCreatedCallback = $callback;

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return this
     */
    public function onModelQuery($callback)
    {
        $this->onModelQueryCallback = $callback;

        return $this;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return array_merge(
            [
                File::make(__('File'))->rules(['required', 'file']),
            ],
            $this->additionalFields
        );
    }


    public function showImportOnIndex(bool $bool = true)
    {
        $this->showImportActionOnIndex = $bool;

        return $this;
    }

    public function showImportOnDetail(bool $bool = true)
    {
        $this->showImportActionOnDetail = $bool;

        return $this;
    }

    /**
     * Prepare the action for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_replace(
            parent::jsonSerialize(),
            [
                'showImportOnIndex' => $this->showImportActionOnIndex,
                'showImportOnDetail' => $this->showImportActionOnDetail
            ]
        );
    }
}
