<?php

namespace App\Models\Authentication;

use App\Models\Ignug\Catalogue;
use App\Models\Ignug\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $connection = 'pgsql-authentication';
    const MENU = 'MENU';
    const MEGA_MENU = 'MEGA_MENU';
    const TYPE_MENUS = 'MENUS';
    const TYPE_STATUS = 'ROUTE_STATUS';
    const URI_MODULE_IGNUG_GRADES = '/grade';
    const URI_MODULE_IGNUG_ATTENDANCES = '/attendance';
    const URI_MODULE_IGNUG_HOMEWORKS = '/homework';
    const URI_MODULE_IGNUG_CLASS = '/class';
    const URI_MODULE_IGNUG_SCHUDLE = '/schudle';
    const URI_MODULE_IGNUG_PORTFOLIO = '/portfolio';
    const URI_MODULE_IGNUG_INCIDENTS = '/incident';
    const URI_MODULE_IGNUG_CALENDAR = '/calendar';
    const URI_MODULE_IGNUG_REPORTS = '/report';
    const URI_MODULE_IGNUG_STUDENTS = '/student';
    const URI_MODULE_IGNUG_EMAIL = '/email';
    const URI_MODULE_IGNUG_TEACHERS = '/teacher';
    const URI_MODULE_IGNUG_PROFILE = '/profile';
    const URI_MODULE_IGNUG_BILLING = '/billing';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class, 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(Catalogue::class, 'status_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function children()
    {
        return $this->hasMany(Route::class, 'parent_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
