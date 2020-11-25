<?php

namespace App\Models\Ignug;

use App\Models\Authentication\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $connection = 'pgsql-ignug';

    protected $fillable = ['old_values', 'new_values', 'description'];

    public function observationable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
