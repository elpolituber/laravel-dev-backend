<?php

namespace App\Models\JobBoard;

use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Ability extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';
    protected $fillable = [
        'description'
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function category()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

}
