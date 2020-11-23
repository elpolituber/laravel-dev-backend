<?php

namespace App\Models\teacherEval;


use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SelfResult extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-teacher-eval';

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function answerQuestion()
    {
        return $this->belongsTo(AnswerQuestion::class);
    }
}
