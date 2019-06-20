<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Fields\File;
use Laravel\Nova\Actions\Action;

class ImportExcel extends Action
{
    public function __construct()
    {
        $this->withoutActionEvents();
        $this->withoutConfirmation();
        $this->onlyOnDetail(false);
        $this->onlyOnIndex(false);
        $this->availableForEntireResource(false);
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
