<?php

namespace App\Models\TeacherEval;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class Answer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-teacher-eval';

    protected $fillable = [
        'code',
        'order',
        'name',
        'value',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'answer_question','answer_id','question_id')->withPivot('id')->withTimestamps();
    }

}
