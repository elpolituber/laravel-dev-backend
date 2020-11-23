<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionalCode extends Model
{
    use HasFactory;
    protected $connection = 'pgsql-authentication';

    protected $fillable = ['username', 'is_valid', 'token'];
}
