<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use App\Models\Authentication\User;
use App\Models\Ignug\State;

class Professional extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'about_me'
    ];

    public function offers()
    {
        return $this->belongsToMany(Offer::class)->withPivot('id', 'status_id')->withTimestamps();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicFormations()
    {
        return $this->hasMany(AcademicFormation::class);
    }

    public function abilities()
    {
        return $this->hasMany(Ability::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function professionalExperiences()
    {
        return $this->hasMany(ProfessionalExperience::class);
    }

    public function professionalReferences()
    {
        return $this->hasMany(ProfessionalReference::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
