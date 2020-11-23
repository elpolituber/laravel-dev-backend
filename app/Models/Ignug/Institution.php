<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Institution extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $connection = 'pgsql-ignug';
    protected $fillable = [
        'code',
        'name',
        'short_name',
        'slogan',
    ];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtolower($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function teachers()
    {
        return $this->morphedByMany(Teacher::class, 'institutionable');
    }

    public function authorities()
    {
        return $this->morphedByMany(Authority::class, 'institutionable');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
