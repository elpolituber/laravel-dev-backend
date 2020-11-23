<?php

namespace App\Models\TeacherEval;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use OwenIt\Auditing\Contracts\Auditable;

class Evaluation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-teacher-eval';
    protected $fillable=[
        'result'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function evaluationType()
    {
        return $this->belongsTo(EvaluationType::class);
    }
    public function pairResult()
    {
        return $this->hasMany(PairResult::class);
    }
    public function detailEvaluations()
    {
        return $this->hasMany(DetailEvaluation::class);
    }

}
