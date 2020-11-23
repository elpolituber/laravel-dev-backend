<?php

namespace App\Models\Community;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ignug\Catalogue;
//use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model
{
    //use \OwenIt\Auditing\Auditable;
   // protected $table="vinculacion.projects";
    protected $connection = 'pgsql-community';
    //utilizacion para el tipo json 
    protected $casts=[
        'cycle'=>'array',
        'bibliografia'=>'array',
    ];
    //
    public function CharitableInstitution(){
        return $this->belongsTo(CharitableInstitution::class);
    }
    public function status(){
        return $this->belongsTo(Catalogue::class,'status_id');
    }
    public function assigned_line(){
        return $this->belongsTo(Catalogue::class,'assigned_line_id');
    }
    public function fraquency(){
        return $this->belongsTo(Catalogue::class,'fraquency_id');
    }
}
