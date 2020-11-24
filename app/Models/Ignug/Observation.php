<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $connection = 'pgsql-ignug';

    public function observationable()
    {
        return $this->morphTo();
    }
}
