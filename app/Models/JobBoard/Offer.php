<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Location;
use App\Models\Ignug\State;


class Offer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';

    protected $fillable = [
        'code',
        'contact',
        'email',
        'phone',
        'cell_phone',
        'position',
        'training_hours',
        'experience_time',
        'remuneration',
        'working_day',
        'number_jobs',
        'start_date',
        'end_date',
        'activities',
        'aditional_information',
        'location_id',
        'state_id'
    ];

    protected $casts = [
        'activities' => 'array',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function professionals()
    {
        return $this->belongsToMany(Professional::class)->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

}
