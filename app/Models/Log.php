<?php

namespace App\Models;

use App\Models\Authentication\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'summary',
        'detail',
        'data',
        'status',
        'message',
        'url',
        'user_id',
    ];

}
