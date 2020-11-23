<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class AcademicFormation extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';

    protected $fillable = [
        'registration_date',
        'senescyt_code',
        'has_titling',
    ];


    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function state()
    {
        return $this->belongsTo(State :: class);
    }

}
