<?php

namespace Maatwebsite\LaravelNovaExcel\Fields;

use Laravel\Nova\Fields\Field;

class RetryButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component      = 'import-retry-button';
    public $showOnCreation = false;
    public $showOnUpdate   = false;
    public $showOnDetail   = false;

    /**
     * @param array $arguments
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(null);
    }
}
