<?php

namespace App\Models\Authentication;

use App\Models\Ignug\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortcut extends Model
{
    use HasFactory;

    const TYPE = 'SHORTCUTS';

    protected $fillable = ['image'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
