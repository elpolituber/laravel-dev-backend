<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Authority extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $connection = 'pgsql-ignug';
    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'functions',
    ];
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'functions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorityType()
    {
        return $this->belongsTo(AuthorityType::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
