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

class Task extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use StatusActiveTrait;
    use StatusDeletedTrait;

    protected $connection = 'pgsql-attendance';

    protected $fillable = [
        'description',
        'percentage_advance'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class);
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
