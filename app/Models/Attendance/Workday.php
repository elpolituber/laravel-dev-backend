<?php

namespace App\Models\Attendance;

use App\Models\Ignug\Observation;
use App\Traits\StatusActiveTrait;
use App\Traits\StatusDeletedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;
use App\Models\Ignug\Catalogue;

class Workday extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use StatusActiveTrait;
    use StatusDeletedTrait;

    protected $connection = 'pgsql-attendance';

    public $data;
    public $catalogues;

    const WORK = "WORK";
    const LUNCH = "LUNCH";
    const TYPE_WORKDAYS = "TYPE_WORKDAYS";

    protected $fillable = [
        'start_time',
        'end_time',
        'description',
        'duration'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class,'type_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function observations()
    {
        return $this->morphMany(Observation::class, 'observationable');
    }
}
