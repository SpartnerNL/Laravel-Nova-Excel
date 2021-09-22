<?php

namespace Maatwebsite\LaravelNovaExcel\Interactions;

use Laravel\Nova\Fields\Text;

trait AskForFilename
{
    /**
     * Ask the user for a filename.
     *
     * @param  string  $label  Input label
     * @param  callable|null  $callback
     * @return $this
     */
    public function askForFilename(string $label = null, callable $callback = null)
    {
        $field = Text::make($label ?? __('Filename'), 'filename');

        if (is_callable($callback)) {
            $callback($field);
        }

        $this->actionFields[] = $field;

        return $this;
    }
}
