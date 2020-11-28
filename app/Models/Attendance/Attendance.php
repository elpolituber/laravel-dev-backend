<?php

namespace App\Models\Attendance;

use App\Models\Ignug\Institution;
use App\Models\Ignug\Observation;
use App\Traits\StatusActiveTrait;
use App\Traits\StatusDeletedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ignug\Teacher;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;
use App\Models\Ignug\Catalogue;

class Attendance extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use StatusActiveTrait;
    use StatusDeletedTrait;

    protected $connection = 'pgsql-attendance';

    const PROCESS_RECTOR = 'RECTOR';
    const PROCESS_VICERRECTOR = 'VICERRECTOR';
    const PROCESS_CONCIERGE = 'CONCIERGE';
    const PROCESS_ACADEMIC = 'ACADEMIC';
    const PROCESS_ADMINISTRATIVE = 'ADMINISTRATIVE';
    const PROCESS_ENTAILMENT = 'ENTAILMENT';
    const PROCESS_INVESTIGATION = 'INVESTIGATION';
    const TYPE_PROCESSES = 'PROCESSES';
    const TYPE_ACTIVITIES = 'ACTIVITIES';

    protected $fillable = [
        'date'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    public function attendanceable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function workdays()
    {
        return $this->hasMany(Workday::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function observations()
    {
        return $this->morphMany(Observation::class, 'observationable');
    }

}
