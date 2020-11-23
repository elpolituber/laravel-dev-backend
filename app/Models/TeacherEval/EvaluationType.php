<?php

namespace App\Models\TeacherEval;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class EvaluationType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-teacher-eval';

    protected $fillable = [
        'name',
        'code',
        'percentage',
        'global_percentage',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function parent()
    {
        return $this->belongsTo(EvaluationType::class, 'parent_id');
    }
}
