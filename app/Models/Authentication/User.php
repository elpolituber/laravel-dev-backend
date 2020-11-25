<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\StatusActiveTrait;
use App\Traits\StatusDeletedTrait;

use App\Models\Attendance\Attendance;
use App\Models\Ignug\Authority;
use App\Models\Ignug\Career;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\Image;
use App\Models\Ignug\Institution;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Professional;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable, HasFactory;
    use \OwenIt\Auditing\Auditable;
    use StatusActiveTrait;
    use StatusDeletedTrait;

    protected $connection = 'pgsql-authentication';

    const ATTEMPTS = 3;

    protected $fillable = [
        'identification',
        'first_name',
        'second_name',
        'first_lastname',
        'second_lastname',
        'personal_email',
        'birthdate',
        'avatar',
        'username',
        'email',
        'email_verified_at',
        'password',
        'change_password',
        'attempts'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }

    public function authority()
    {
        return $this->hasOne(Authority::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function ethnicOrigin()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function location()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function identificationType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function sex()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function gender()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function careers()
    {
        return $this->morphToMany(Career::class, 'careerable' . 'ignug.careerables');
    }

    public function institutions()
    {
        return $this->morphToMany(Institution::class, 'institutionable', 'ignug.institutionables');
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendanceable');
    }

    public function attendance()
    {
        return $this->morphOne(Attendance::class, 'attendanceable');
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
