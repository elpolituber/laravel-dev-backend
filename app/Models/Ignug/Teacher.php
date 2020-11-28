<?php

namespace App\Models\Ignug;

use App\Models\Authentication\User;
use App\Models\Attendance\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Teacher extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $connection = 'pgsql-ignug';
    protected $table = 'ignug.teachers';

    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendanceable');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
