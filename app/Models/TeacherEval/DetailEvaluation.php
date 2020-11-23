<?php

namespace App\Models\TeacherEval;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class DetailEvaluation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-teacher-eval';

    protected $fillable = [
        'result'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function detailEvaluationable()
    {
        return $this->morphTo();
    }
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
