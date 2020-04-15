<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @property int         $id
 * @property string|null $disk
 * @property string      $path
 * @property string      filename
 * @property string      resource
 * @property User        user
 */
class Upload extends Model
{
    use Resourceable;

    /**
     * @var string
     */
    protected $table = 'excel_uploads';

    /**
     * @var array
     */
    protected $fillable = [
        'disk',
        'filename',
        'resource',
        'parent_resource',
        'path',
    ];

    /**
     * @param UploadedFile $file
     * @param User         $user
     * @param null         $resource
     *
     * @return static
     */
    public static function forUploadedFile(UploadedFile $file, $user, $resource = null, $parentResource = null)
    {
        return DB::transaction(function () use ($resource, $file, $user, $parentResource) {
            $upload = new static([
                'disk'     => null,
                'resource' => $resource,
                'parent_resource' => $parentResource,
                'filename' => $file->getClientOriginalName()
            ]);

            $upload->user()->associate($user);
            $upload->saveOrFail();
            $upload->linkFile($file);

            return $upload;
        });
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        $guard    = config('nova.guard') ?: config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model    = config("auth.providers.{$provider}.model");

        return $this->belongsTo($model, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    /**
     * @param UploadedFile $file
     */
    public function linkFile(UploadedFile $file)
    {
        $this->update([
            'path' => $file->storeAs('excel_uploads/' . $this->id, $this->filename, array_filter([
                'disk' => $this->disk,
            ])),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(static function (Upload $upload) {
            Storage::disk($upload->disk)->delete($upload->path);
        });
    }
}
