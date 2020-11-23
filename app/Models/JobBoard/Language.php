<?php

namespace App\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class Language extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-job-board';

    protected $fillable = [
        'written_level_id',
        'spoken_level_id',
        'reading_level_id',
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function writtenLevel()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function spokenLevel()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function readingLevel()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
