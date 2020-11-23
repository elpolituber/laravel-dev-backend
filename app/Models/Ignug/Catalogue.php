<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Authentication\Role;
use App\Models\Authentication\Route;

class Catalogue extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-ignug';

    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_MAINTENANCE = 'MAINTENANCE';
    const TYPE_STATUS = 'SYSTEM_STATUS';
    const SEXS_MALE = 'MALE';
    const SEXS_FAMALE = 'FAMALE';
    const TYPE_SEXS = 'SEXS';

    protected $fillable = [
        'code',
        'name',
        'type',
        'icon'
    ];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function parent()
    {
        return $this->belongsTo(Catalogue::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Catalogue::class, 'parent_id');
    }

    // Relaciones Polimorficas
    public function roles()
    {
        return $this->morphedByMany(Role::class, 'catalogueable');
    }
}
