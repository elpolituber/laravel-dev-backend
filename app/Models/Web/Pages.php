<?php

namespace App\models\Web;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pages extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-web';
    protected $fillable = [
        'title',
        'subtitle',
        'description',

    ];

    public function pageable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}