<?php

namespace Maatwebsite\LaravelNovaExcel\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use Maatwebsite\LaravelNovaExcel\Actions\RevertImport;
use Maatwebsite\LaravelNovaExcel\Fields\RetryButton;
use Maatwebsite\LaravelNovaExcel\Models\Import as ImportModel;

class Import extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ImportModel::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        $guard    = config('nova.guard') ?: config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model    = config("auth.providers.{$provider}.model");

        return [
            Text::make('Resource'),
            BelongsTo::make('Imported By', 'user', Nova::resourceForModel($model)),
            BelongsTo::make('File', 'upload', Upload::class),
            Status::make('Status')->loadingWhen(['waiting', 'running'])->failedWhen(['failed']),
            DateTime::make('Imported at', 'created_at'),
            RetryButton::make(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new RevertImport(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $request instanceof ActionRequest;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
