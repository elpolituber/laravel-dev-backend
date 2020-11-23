<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class ProfessionalExperience extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';

    protected $fillable = [
        'employer',
        'position',
        'job_description',
        'start_date',
        'end_date',
        'reason_leave',
        'current_work',
    ];
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
