<?php

namespace Maatwebsite\LaravelNovaExcel;

use Maatwebsite\LaravelNovaExcel\Models\Import;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToImport
{
    /**
     * @return BelongsTo
     */
    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
