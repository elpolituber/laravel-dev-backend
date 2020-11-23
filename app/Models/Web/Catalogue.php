<?php

namespace App\models\Web;
use App\Models\Authentication\Route;
use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Catalogue extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-web';
    protected $fillable = [

        'code',
        'name',
        'type',
        'icon',

    ];


    public function resourceable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
