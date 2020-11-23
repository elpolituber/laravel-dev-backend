<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    const IGNUG_GRADES = 'CALIFICACIONES';
    const IGNUG_ATTENDANCES = 'ASISTENCIAS';
    const IGNUG_HOMEWORKS = 'TAREAS';
    const IGNUG_CLASS = 'CLASE';
    const IGNUG_SCHUDLE = 'HORARIO';
    const IGNUG_PORTFOLIO = 'PORTAFOLIO';
    const IGNUG_INCIDENTS = 'INCIDENTES';
    const IGNUG_CALENDAR = 'CALENDARIO';
    const IGNUG_REPORTS = 'REPORTES';
    const IGNUG_EMAIL = 'CORREO ELECTRONICO';
    const IGNUG_STUDENTS = 'ESTUDIANTES';
    const IGNUG_TEACHERS = 'PROFESORES';
    const IGNUG_PROFILE = 'PERFIL';
    const IGNUG_BILLING = 'FACTURACION';
    const TYPE = 'MODULES';

}
