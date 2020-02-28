<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Resource;
use Maatwebsite\LaravelNovaExcel\Actions\ImportExcel;

trait BelongsToAction
{
    /**
     * @return ImportExcel
     */
    protected function action(): ImportExcel
    {
        return collect($this->resource()->actions($this->request))->first(function (Action $action) {
            return $action instanceof ImportExcel;
        });
    }

    /**
     * @return resource
     */
    abstract protected function resource();
}
