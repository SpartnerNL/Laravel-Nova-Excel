<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        // It seems when running in a worker Nova doesn't load all resources, causing the above to be null
        // Let's manually load all the resources and try again
        if ($resourceName === null) {
            Nova::resourcesIn(app_path('Nova'));
            $model        = Nova::modelInstanceForKey($this->resource);
            $resourceName = Nova::resourceForModel($model);
        }

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
