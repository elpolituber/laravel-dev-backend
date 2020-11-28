<?php

namespace App\Exports;

use App\Models\Attendance\Attendance;
use App\Models\Authentication\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class AttendancesExport implements FromView
{
    use Exportable;

    private $totalSeconds = 0;
    private $totalMinutes = 0;
    private $totalHours = 0;
    private $date;
    private $institutionId;

    public function view(): View
    {

        $users = User::whereHas('institutions', function ($institutions) {
            $institutions->where('institutions.id', $this->institutionId);
        })->has('teacher')
            ->with('institutions')->get();
        $start_13 = (new Carbon($this->date))->subDays(13);
        $end_13 = (new Carbon($this->date))->subDays(1);
        $start_18 = (new Carbon($this->date));
        $end_18 = (new Carbon($this->date))->addDays(17);

        $weekends13 = 0;
        while ($start_13 <= $end_13) {
            if ($start_13->format('l') == 'Saturday' || $start_13->format('l') == 'Sunday') {
                $weekends13++;
            }
            $start_13->modify("+1 days");
        }

        $weekends18 = 0;
        while ($start_18 <= $end_18) {
            if ($start_18->format('l') == 'Saturday' || $start_18->format('l') == 'Sunday') {
                $weekends18++;
            }
            $start_18->modify("+1 days");
        }
        $start_13 = (new Carbon($this->date))->subDays(13);
        $end_13 = (new Carbon($this->date))->subDays(1);
        $start_18 = (new Carbon($this->date));
        $end_18 = (new Carbon($this->date))->addDays(17);
        $reports = array();
        foreach ($users as $user) {
            $attendances13 = $user->attendances()->with(['workdays' => function ($workdays) {
                $workdays->with('type');
            }])
                ->whereBetween('date', [$start_13, $end_13])
                ->get();
            foreach ($attendances13 as $attendance) {
                foreach ($attendance->workdays as $workday) {
                    $this->calculateTotalDuration($workday->duration, $workday->type->code);
                }
            }

            $totalHours13 = $this->totalHours;
            $totalMinutes13 = $this->totalMinutes;
            $totalSeconds13 = $this->totalSeconds;
            if ($totalSeconds13 >= 30) {
                $totalMinutes13++;
            }
            if ($totalMinutes13 >= 30) {
                $totalHours13++;
            }

            $attendances18 = $user->attendances()->with(['workdays' => function ($workdays) {
                $workdays->with('type');
            }])
                ->whereBetween('date', [$start_18, $end_18])
                ->get();

            $this->totalSeconds = 0;
            $this->totalMinutes = 0;
            $this->totalHours = 0;
            foreach ($attendances18 as $attendance) {
                foreach ($attendance->workdays as $workday) {
                    $this->calculateTotalDuration($workday->duration, $workday->type->code);
                }
            }
            $totalHours18 = $this->totalHours;
            $totalMinutes18 = $this->totalMinutes;
            $totalSeconds18 = $this->totalSeconds;
            if ($totalSeconds18 >= 30) {
                $totalMinutes18++;
            }
            if ($totalMinutes18 >= 30) {
                $totalHours18++;
            }
            if (sizeof($attendances13) > 0 || sizeof($attendances18) > 0) {
                array_push($reports,
                    [
                        'month' => $start_18->formatLocalized('%B'),
                        'user' => $user,
                        'institution' => $user->institutions[0]->denomination . ' ' . $user->institutions[0]->name,
                        'days13' => ($totalHours13 / 8) + $weekends13,
                        'days18' => ($totalHours18 / 8) + $weekends18
                    ]);
            }
            $this->totalSeconds = 0;
            $this->totalMinutes = 0;
            $this->totalHours = 0;
        }

        return view('reports.attendance.workdays', [
            'reports' => $reports
        ]);
    }

    public function institutionId(int $institutionId)
    {
        $this->institutionId = $institutionId;
        return $this;
    }

    public function date(string $date)
    {
        $this->date = $date;
        return $this;
    }

    private function calculateTotalDuration($time, $type)
    {
        $hour = substr($time, 0, 2);
        $minute = substr($time, 3, 2);
        $second = substr($time, 6, 2);

        if ($type === 'WORK') {
            $this->totalSeconds += (int)$second;
            $this->totalMinutes += (int)$minute;
            $this->totalHours += (int)$hour;

            if ($this->totalSeconds >= 60) {
                $this->totalSeconds = 0;
                $this->totalMinutes += 1;
            }

            if ($this->totalMinutes >= 60) {
                $this->totalMinutes = $this->totalMinutes - 60;
                $this->totalHours += 1;
            }
        }

        if ($type === 'LUNCH') {
            if (($this->totalSeconds - (int)$second) < 0) {
                $this->totalSeconds += 60;
                $this->totalSeconds -= (int)$second;
                $this->totalMinutes -= 1;
            } else {
                $this->totalSeconds -= (int)$second;
            }

            if (($this->totalMinutes - (int)$minute) < 0) {
                $this->totalMinutes += 60;
                $this->totalMinutes -= (int)$minute;
                $this->totalHours -= 1;
            } else {
                $this->totalMinutes -= (int)$minute;
            }

            $this->totalHours -= (int)$hour;
        }
    }
}
