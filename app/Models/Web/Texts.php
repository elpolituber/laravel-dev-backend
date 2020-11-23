<?php

namespace App\models\Web;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Texts extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-web';
    protected $fillable = [
        'type_id',

    ];

    public function textable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
