<?php

namespace App\Exports;

use App\Models\Attendance\Attendance;
use App\Models\Authentication\User;
use App\Models\Ignug\Institution;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TasksExport implements FromView
{
    use Exportable;

    private $institutionId;
    private $startDate;
    private $endDate;

    public function view(): View
    {

        $institution = Institution::findOrFail($this->institutionId);
        $users = User::whereHas('institutions', function ($institutions) {
            $institutions->where('institutions.id', $this->institutionId);
        })->has('teacher')->orderBy('first_lastname')->get();

        $startDate = new Carbon($this->startDate);
        $endDate = new Carbon($this->endDate);
        $date = 'Del ' . $startDate->format('d') . ' de ' . $startDate->formatLocalized('%B')
            . ' al ' . $endDate->format('d') . ' de ' . $endDate->formatLocalized('%B');
        $reports = array();
        foreach ($users as $user) {
            $attendances = $user->attendances()->with(['tasks' => function ($workdays) {
                $workdays->with(['type' => function ($type) {
                    $type->with(['parent' => function ($parent) {
                        $parent->orderBy('name');
                    }]);
                }]);
            }])
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->orderBy('date')
                ->get();
            $tasks = array();
            $processes = array();
            foreach ($attendances as $attendance) {
                foreach ($attendance->tasks as $task) {
                    array_push($tasks, $task);
                }
            }


            //if (sizeof($attendances) > 0) {
            array_push($reports,
                [
                    'attendances' => $attendances,
                    'user' => $user,
                    'tasks' => $tasks
                ]);
            //}
            $task = array();
        }

        return view('reports.attendance.tasks', [
            'reports' => $reports, 'institution' => $institution, 'date' => $date
        ]);
    }

    public function institutionId(int $institutionId)
    {
        $this->institutionId = $institutionId;
        return $this;
    }

    public function startDate(string $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function endDate(string $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }
}
