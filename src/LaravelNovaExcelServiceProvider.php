<?php

namespace Maatwebsite\LaravelNovaExcel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Fields\Field;
use Maatwebsite\LaravelNovaExcel\Requests\ExportActionRequest;

class LaravelNovaExcelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->addExportHelperMacrosToNovaFields();
    }

    protected function addExportHelperMacrosToNovaFields()
    {
        Field::macro('onlyOnExport', function () {
            // LaravelNovaExcel only uses fields that are visible in the index
            return $this
                // First hide it everywhere except on indexes
                ->onlyOnIndex()
                // Then decide when to show it when loaded an index
                ->showOnIndex(function (Request $request) {
                    return $request instanceof ExportActionRequest;
                });
        });

        Field::macro('showOnExport', function () {
            // LaravelNovaExcel only uses fields that are visible in the index
            return $this
                // In this case we don't care what other places the field is show
                ->showOnIndex(function (Request $request) {
                    return $request instanceof ExportActionRequest;
                });
        });

        Field::macro('hideOnExport', function () {
            // LaravelNovaExcel only uses fields that are visible in the index
            return $this
                // This way we decide to hide it on exports
                ->showOnIndex(function (Request $request) {
                    return !$request instanceof ExportActionRequest;
                });
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
             ->prefix('nova-vendor/maatwebsite/laravel-nova-excel')
             ->group(__DIR__ . '/../routes/api.php');
    }
}
