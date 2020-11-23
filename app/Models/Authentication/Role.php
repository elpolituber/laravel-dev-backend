<?php

namespace App\Models\Authentication;

use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-authentication';
    const ROLE_IGNUG_ADMIN = 'ADMIN';
    const ROLE_IGNUG_TEACHER = 'TEACHER';
    const ROLE_IGNUG_STUDENT = 'STUDENT';
    const ROLE_IGNUG_AGENT = 'AGENT';
    const ROLE_IGNUG_RECTOR = 'RECTOR';
    const ROLE_IGNUG_VICERRECTOR = 'VICERRECTOR';
    const ROLE_IGNUG_CONCIERGE = 'CONCIERGE';
    const ROLE_SERD_ADMIN = 'ADMIN';
    const ROLE_SERD_TEACHER = 'TEACHER';
    const URI_DASHBOARD = '/dashboard';

    protected $fillable = [
        'code',
        'name',
        'uri',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function shortcuts()
    {
        return $this->hasMany(Shortcut::class);
    }

    public function catalogues()
    {
        return $this->morphToMany(Catalogue::class,'catalogueable');
    }
}
