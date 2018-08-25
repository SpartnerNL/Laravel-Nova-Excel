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
    public function askForFilename()
    {
        $this->actionFields[] = Text::make(__('Filename'), 'filename');

        return $this;
    }
}