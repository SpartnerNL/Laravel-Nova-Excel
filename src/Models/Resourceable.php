<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

trait Resourceable
{
    /**
     * @return resource
     */
    public function getResourceInstance(): Resource
    {
        $model        = Nova::modelInstanceForKey($this->resource);
        $resourceName = Nova::resourceForModel($model);

        return new $resourceName($model);
    }

    /**
     * @return Model
     */
    public function getModelInstance()
    {
        return Nova::modelInstanceForKey($this->resource);
    }
}
