<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

interface ExportActionRequest
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery();

    /**
     * @return array
     */
    public function indexFields(): array;
}