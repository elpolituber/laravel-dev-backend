<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    const IGNUG = 'IGNUG';
    const SERD = 'SERD';
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
