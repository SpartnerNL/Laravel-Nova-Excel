<?php

namespace Maatwebsite\LaravelNovaExcel\Interactions;

use Laravel\Nova\Fields\Select;
use Maatwebsite\Excel\Excel;

trait AskForWriterType
{
    /**
     * Ask the user for a filename.
     *
     * @param  array|null  $options
     * @param  string|null  $label
     * @param  callable|null  $callback
     * @return $this
     */
    public function askForWriterType(array $options = null, string $label = null, callable $callback = null)
    {
        $options = $options ?: [
            Excel::XLS  => 'XLS',
            Excel::XLSX => 'XLSX',
            Excel::CSV  => 'CSV',
        ];

        $field = Select::make(__($label ?: 'Type'), 'writer_type')->options($options);

        if (is_callable($callback)) {
            $callback($field);
        }

        $this->actionFields[] = $field;

        return $this;
    }
}
