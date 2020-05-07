<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Maatwebsite\LaravelNovaExcel\Imports\ResourceImport;

class ReImportExcel extends ImportExcel
{
    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->withConfirmation();
        $this->visibleImport(false);
        $this->showImportOnDetail();
    }

    /**
     * @return string
     */
    public function uriKey()
    {
        return 're-import-excel';
    }
}
