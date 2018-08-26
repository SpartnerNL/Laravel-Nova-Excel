<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedExport extends ExportToExcel implements ShouldQueue
{
    /**
     * @var string
     */
    public $name = 'Export to Excel';
}
