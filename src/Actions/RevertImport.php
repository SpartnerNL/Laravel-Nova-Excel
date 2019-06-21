<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Actions\Action;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Actions\ActionModelCollection;

class RevertImport extends DestructiveAction
{
    /**
     * @param ActionFields          $fields
     * @param ActionModelCollection $models
     *
     * @return array
     */
    public function handle(ActionFields $fields, ActionModelCollection $models)
    {
        DB::transaction(function () use ($models) {
            foreach ($models as $import) {
                $import->getModelInstance()->newQuery()->where('import_id', $import->getKey())->delete();
                $import->update([
                    'status' => 'reverted',
                ]);
            }
        });

        return Action::message('Imports reverted');
    }
}
