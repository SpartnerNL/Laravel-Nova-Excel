<?php

namespace Maatwebsite\LaravelNovaExcel\Interactions;

use Laravel\Nova\Fields\Text;

trait AskForFilename
{
    /**
     * Ask the user for a filename.
     *
     * @return $this
     */
    public function askForFilename(string $label = null)
    {
        $this->actionFields[] = Text::make($label ?? __('Filename'), 'filename');

        return $this;
    }
}
