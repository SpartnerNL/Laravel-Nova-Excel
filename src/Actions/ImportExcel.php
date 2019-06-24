<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Fields\File;
use Laravel\Nova\Actions\Action;

class ImportExcel extends Action
{
    /**
     * @var bool
     */
    protected $headingRow = true;

    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->withoutActionEvents();
        $this->withoutConfirmation();
        $this->onlyOnDetail(false);
        $this->onlyOnIndex(false);
        $this->availableForEntireResource(false);
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
     * @return string
     */
    public function uriKey()
    {
        return 'import-excel';
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            File::make(__('File')),
        ];
    }
}
