## Notes for the Nova Import Module (WIP)

Below are two ways of setting up the Import Module. In the first way we will set it up directly on the model we want to import. In the second case we will set it up on a parent model.

### Direct Import
Please follow the basic steps below to make a model importable

- Add the `\Maatwebsite\LaravelNovaExcel\Tools\Import` to your tools
- Add the `BelongsToImport` to your model
- Add the `ImportExcel` action to your resource actions
- Run migrations


### Relational Import
Allow a model to be imported through a relation, you need to follow the steps below

- Add the `\Maatwebsite\LaravelNovaExcel\Tools\Import` to your tools
- Add the `BelongsToImport` to the model you would like to import
- Add the `ImportExcel` action to both the resources, the parent and relation as follows:
  - <b>Parent</b>
    Before adding the action, make sure to add the following static property to your Parent's resource and replace `{RELATION}` with your relations resource class:
    ```PHP
    public static $import_as = {RELATION}::class;
    ```
    Add the action as below, this will only make it show on the index page:
    ```PHP
    public function actions(Request $request)
    {
        return [
            (new \Maatwebsite\LaravelNovaExcel\Actions\ImportExcel('Import List'))
                ->onlyOnIndex(true)
        ];
    }
    ```
    It's also possible to add custom meta data using fields which can be used later on after the import as follows:
    ```PHP
    public function actions(Request $request)
    {
        return [
            (new \Maatwebsite\LaravelNovaExcel\Actions\ImportExcel('Import List'))
                ->onlyOnIndex(true)
                ->addField(Text::make('name'))
        ];
    }
    ```
  - <b>Relation</b>
    Add the action as below, this should hide it from all the views but currenlty due a bug in Nova it still shows. The after function isn't required, but is the only way to for example hook the imported models to their parent model, this isn't done automatically because of all the differences in different codebases:
    ```PHP
    public function actions(Request $request)
    {
        return [
            (new \Maatwebsite\LaravelNovaExcel\Actions\ImportExcel())
                ->visible(false)
                ->after(function (Collection $models, object $meta) {
                    // Do stuff after the import with the imported models and meta data
                    // For example create the parent model and link it to the models from the import
                })
        ];
    }
    ```
