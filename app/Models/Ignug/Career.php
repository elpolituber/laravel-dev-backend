<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Career extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $connection = 'pgsql-ignug';

    protected $fillable = [
        'code',
        'name',
        'description',
        'resolution_number',
        'title',
        'acronym',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function modality()
    {
        return $this->belongsTo(Catalogue::class, 'modality_id');
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class, 'type_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
