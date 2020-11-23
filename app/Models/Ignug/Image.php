<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class Image extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $connection = 'pgsql-ignug';

    const AVATAR_TYPE = 'avatars';
    const IMAGE_TYPE = 'images';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'uri',
        'extension',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public static function upload($model, $request)
    {
        $fileCode = Carbon::now();
        $filePath = $request->image->storeAs('images', $request->name . '.png', 'public');
        $image = $model->images()->where('type', Image::IMAGE_TYPE)->where('name', $request->name)->first();
        if (!$image) {
            $avatar = new Image([
                'code' => $fileCode,
                'name' => $request->name,
                'description' => $request->description,
                'type' => Image::IMAGE_TYPE,
                'uri' => $filePath,
            ]);

            $avatar->imageable()->associate($model);
            $avatar->state()->associate(State::firstWhere('code', State::ACTIVE));
            $avatar->save();
        }

        return $image;

    }
}
