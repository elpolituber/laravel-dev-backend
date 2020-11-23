<?php

namespace App\Models\JobBoard;

use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProfessionalReference extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';


    protected $fillable = [
        'institution',
        'position',
        'contact',
        'phone',
        'state',
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
