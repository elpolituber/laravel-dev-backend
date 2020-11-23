<?php

namespace App\models\Web;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Settings extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-web';
    protected $fillable = [
        'value',
        'type_id',
        'image',

    ];

    public function settingable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}