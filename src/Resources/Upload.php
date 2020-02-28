<?php

namespace Maatwebsite\LaravelNovaExcel\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Resource;
use Maatwebsite\LaravelNovaExcel\Models\Upload as UploadModel;

class Upload extends Resource
{
    /**
     * @var string
     */
    public static $title = 'filename';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = UploadModel::class;

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
        return [];
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
