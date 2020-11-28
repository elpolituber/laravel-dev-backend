<?php

namespace App\Models\Ignug;

use App\Models\Attendance\Attendance;
use App\Models\Authentication\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Institution extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-ignug';
    protected $table = 'ignug.institutions';
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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'institutionable', 'ignug.institutionables');
    }
}
