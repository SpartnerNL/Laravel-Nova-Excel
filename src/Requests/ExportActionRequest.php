<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Http\Requests\ActionRequest;

class ExportActionRequest extends ActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function getExportQuery()
    {
        return $this->toSelectedResourceQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        });
    }
}
