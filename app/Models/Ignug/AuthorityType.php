<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuthorityType extends Model implements Auditable
{
    protected $connection = 'pgsql-ignug';
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
    ];

    public function authorities()
    {
        return $this->hasMany(Authority::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
