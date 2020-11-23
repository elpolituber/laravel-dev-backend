<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class Course extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';

    protected $fillable = [
        'event_name',
        'start_date',
        'end_date',
        'hours',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function institution()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function eventType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function certificationType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
