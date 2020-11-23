<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class Location extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';
    protected $fillable = [
        'code',
        'name',
        'type',
        'post_code'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }
}
