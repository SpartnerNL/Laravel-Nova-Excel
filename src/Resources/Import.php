<?php

namespace Maatwebsite\LaravelNovaExcel\Resources;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Status;
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Resource'),

            // TODO: get user resource via config
            Text::make('Imported By', 'user')->resolveUsing(function($user) {
                return $user->name;
            }),

            BelongsTo::make('File', 'upload', Upload::class),

            Status::make('Status')->loadingWhen(['waiting', 'running'])->failedWhen(['failed']),
            RetryButton::make(),
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
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

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return false;
    }
}