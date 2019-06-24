<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\LaravelNovaExcel\BelongsToImport;

trait KeepsTrackOfImport
{
    /**
     * @param Model $model
     *
     * @return bool
     */
    protected function shouldKeepTrackOfImport(Model $model)
    {
        return in_array(BelongsToImport::class, trait_uses_recursive($model));
    }

    /**
     * @param Model $model
     */
    protected function associateImport(Model $model)
    {
        $model->import()->associate($this->import);
    }
}
