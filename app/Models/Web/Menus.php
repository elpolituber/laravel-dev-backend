<?php

namespace App\models\Web;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class Menus extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-web';
    protected $fillable = [

        'name',
        'parent_id',
        'type_id',
        'state_id',

    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
