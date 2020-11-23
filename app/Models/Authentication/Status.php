<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    const ACTIVE = '1';
    const INACTIVE = '2';
    const LOCKED = '3';

    protected $fillable = ['code', 'name'];
}
