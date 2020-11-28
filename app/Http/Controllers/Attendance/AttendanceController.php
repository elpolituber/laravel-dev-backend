<?php

namespace App\Http\Controllers\Attendance;

use App\Exports\AttendancesExport;
use App\Exports\TasksExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\Task;
use App\Models\Attendance\Workday;
use App\Models\Authentication\User;
use App\Models\Ignug\Institution;
use App\Models\Ignug\Observation;
use App\Models\Ignug\State;
use App\Models\Ignug\Catalogue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function getCurrentDay(Request $request)
    {
        $users = User::with(['attendance' => function ($attendances) use ($request) {
            $attendances->with(['workdays' => function ($workdays) use ($request) {
                $workdays->with('observations')->with('type');
            }])
                ->with(['tasks' => function ($tasks) {
                    $tasks->with('observations')->with(['type' => function ($type) {
                        $type->with(['parent' => function ($parent) {
                            $parent->orderBy('name');
                        }]);
                    }]);
                }])
                ->where('institution_id', $request->institution_id)
                ->where('date', $request->date);
        }])->with(['institutions' => function ($institutions) use ($request) {
            $institutions->where('institution_id', $request->institution_id);
        }])
            ->get();

        if (sizeof($users) === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No existen profesores',
                    'detail' => '',
                    'code' => '404',
                ]], 404);
        }
        return response()->json([
            'data' => $users,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getHistoryAttendances(Request $request)
    {
        $attendances = Attendance::
        with(['workdays' => function ($workdays) {
            $workdays
                ->with('observations')->with('type');
        }])
            ->with(['tasks' => function ($tasks) {
                $tasks
                    ->with('observations')->with(['type' => function ($type) {
                        $type->with('parent');
                    }]);
            }])
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->where('institution_id', $request->institution_id)
            ->get();
        return response()->json([
            'data' => $attendances,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getTotalProcesses(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $processes = Catalogue::where('type', $catalogues['task']['process']['type'])->orderBy('name')->get();
        $attendances = Attendance::with(['workdays' => function ($workdays) {
            $workdays
                ->with('type');
        }])
            ->with(['tasks' => function ($tasks) {
                $tasks
                    ->with(['type' => function ($type) {
                        $type->with('parent');
                    }]);
            }])
            ->where('institution_id', $request->institution_id)
            ->get();

        $data = array();
        $labels = array();
        $backgroundColor = array();

        foreach ($processes as $process) {
            $total = 0;
            foreach ($attendances as $attendance) {
                foreach ($attendance['tasks'] as $task) {
                    $total += $task['type']['parent']['id'] === $process->id ? 1 : 0;
                }
            }
            array_push($data, $total);
            array_push($labels, $process['name']);
            array_push($backgroundColor, $process['color']);
        }
        $response = ['data' => $data, 'labels' => $labels, 'background_color' => $backgroundColor];

        return response()->json([
            'data' => $response,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getUserAttendances(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $attendances = $user->attendances()
            ->with(['workdays' => function ($workdays) {
                $workdays
                    ->with('observations')->with('type');
            }])
            ->with(['tasks' => function ($tasks) {
                $tasks
                    ->with('observations')->with(['type' => function ($type) {
                        $type->with('parent');
                    }]);
            }])
            ->where('institution_id', $request->institution_id)
            ->get();
        return response()->json([
            'data' => $attendances,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getUserHistoryAttendances(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $attendances = $user->attendances()
            ->with(['workdays' => function ($workdays) {
                $workdays
                    ->with('observations')->with('type');
            }])
            ->with(['tasks' => function ($tasks) {
                $tasks
                    ->with('observations')->with(['type' => function ($type) {
                        $type->with('parent');
                    }]);
            }])
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->where('institution_id', $request->institution_id)
            ->get();
        return response()->json([
            'data' => $attendances,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getUserCurrentDay(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $attendances = $user->attendances()
            ->with(['workdays' => function ($workdays) {
                $workdays->with('observations')->with('type');
            }])
            ->with(['tasks' => function ($tasks) {
                $tasks->with('observations')->with(['type' => function ($type) {
                    $type->with(['parent' => function ($parent) {
                        $parent->orderBy('name');
                    }]);
                }]);
            }])
            ->where('institution_id', $request->institution_id)
            ->where('date', Carbon::now())
            ->first();
        if (!$attendances) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No has iniciado jornada',
                    'detail' => 'Haz click para iniciar',
                    'code' => '404',
                ]], 404);
        }
        return response()->json([
            'data' => $attendances,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function startDay(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];
        $user = User::findOrFail($request->user_id);

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Tu usuario no tiene asignado un profesor',
                    'detail' => 'Comunicate con el administrador',
                    'code' => '404',
                ]], 404);
        }
        $attendance = $user->attendances()->where('date', $request->date)->where('institution_id', $request->institution_id)->first();

        if (!$attendance) {
            $attendance = $this->createAttendance($request->institution_id, $request->date, $user);
        }
        if ($dataWorkday['type']['code'] === $catalogues['workday']['type']['work']) {
            $works = $attendance->workdays()
                ->where('type_id', Catalogue::where('code', $catalogues['workday']['type']['work'])
                    ->where('type', $catalogues['workday']['type']['type'])->first()->id)
                ->get();
            if (sizeof($works) > 1) {
                return response()->json([
                    'data' => null,
                    'msg' => [
                        'summary' => 'Ha excedido el limite maximo',
                        'detail' => 'No puede iniciar otra jornada',
                        'code' => '403',
                    ]], 403);
            }

        }

        if ($dataWorkday['type']['code'] == $catalogues['workday']['type']['lunch']) {
            $lunchs = $attendance->workdays()
                ->where('type_id', Catalogue::where('code', $catalogues['workday']['type']['lunch'])
                    ->where('type', $catalogues['workday']['type']['type'])->first()->id)
                ->get();

            if (sizeof($lunchs) > 0) {
                return response()->json([
                    'data' => null,
                    'msg' => [
                        'summary' => 'Ha excedido el limite maximo',
                        'detail' => 'No puede iniciar otro almuerzo',
                        'code' => '403',
                    ]], 403);
            }
        }

        $this->createWorkday($request->user(), $dataWorkday, $attendance);

        return response()->json([
            'data' => $user->attendances()->with(['workdays' => function ($workdays) {
                $workdays->with('type')->orderBy('start_time');
            }])->with(['tasks' => function ($tasks) {
                $tasks->with(['type' => function ($type) {
                    $type->with(['parent' => function ($parent) {
                        $parent->orderBy('name');
                    }]);
                }]);
            }])
                ->where('institution_id', $request->institution_id)
                ->where('date', Carbon::now())
                ->first(),
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201',
            ]], 201);
    }

    public function endDay(Request $request)
    {
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];

        $workday = Workday::findOrFail($dataWorkday['id']);

        if ($workday) {
            $workday->update([
                'end_time' => $dataWorkday['end_time'],
                'duration' => $this->calculateDuration($workday->start_time->format('H:i:s'), $dataWorkday['end_time'])
            ]);

            $observation = new Observation([
                'old_values' => null,
                'new_values' => 'Hora fin: ' . $dataWorkday['end_time'],
                'description' => $dataWorkday['observation']
            ]);

            $observation->state()->associate(State::firstWhere('code', State::ACTIVE));
            $observation->user()->associate($request->user());
            $observation->observationable()->associate($workday);
            $observation->save();
        }

        $workdays = Workday::where('attendance_id', $workday['attendance_id'])
            ->with('type')
            ->orderBy('start_time')
            ->get();
        return response()->json([
            'data' => $workdays,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 201);
    }

    public function updateDay(Request $request)
    {
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];

        $workday = Workday::findOrFail($dataWorkday['id']);

        if ($workday) {
            $observation = new Observation([
                'old_values' => 'Hora inicio: ' . $workday->start_time . ' | ' . 'Hora fin: ' . $workday->end_time,
                'new_values' => 'Hora inicio: ' . $dataWorkday['start_time'] . ' | ' . 'Hora fin: ' . $dataWorkday['end_time'],
                'description' => $dataWorkday['observation']
            ]);

            $workday->update([
                'end_time' => $dataWorkday['end_time'],
                'start_time' => $dataWorkday['start_time'],
                'duration' => $this->calculateDuration($dataWorkday['start_time'], $dataWorkday['end_time'])
            ]);

            $observation->state()->associate(State::firstWhere('code', State::ACTIVE));
            $observation->user()->associate($request->user());
            $observation->observationable()->associate($workday);
            $observation->save();
        }

        $workdays = Workday::where('attendance_id', $workday['attendance_id'])
            ->with('type')
            ->orderBy('start_time')
            ->get();
        return response()->json([
            'data' => $workdays,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 201);
    }

    public function registerTask(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $data = $request->json()->all();
        $dataTask = $data['task'];

        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->where('date', $request->date)->where('institution_id', $request->institution_id)->first();

        if ($attendance) {
            $this->createOrUpdateTask($request->user(), $dataTask, $attendance);
        } else {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Asistencia no encontrada',
                    'detail' => 'Debes iniciar primero tu jornada',
                    'code' => '404',
                ]], 404);
        }

        return response()->json([
            'data' => $user->attendances()->with(['workdays' => function ($workdays) {
                $workdays->with('type')->orderBy('start_time');
            }])->with(['tasks' => function ($tasks) {
                $tasks->with(['type' => function ($type) {
                    $type->with(['parent' => function ($parent) {
                        $parent->orderBy('name');
                    }]);
                }]);
            }])
                ->where('institution_id', $request->institution_id)
                ->where('date', Carbon::now())
                ->first(),
            'msg' => [
                'summary' => 'Success',
                'detail' => 'Se guardó correctamente la actividad',
                'code' => '201',
            ]], 201);
    }

    public function getProcess(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $process = Catalogue::with(['children' => function ($children) {
            $children->orderBy('name');
        }])->where('type', $catalogues['task']['process']['type'])->orderBy('name')->get();
        return response()->json([
            'data' => $process,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    private function createAttendance($institutionId, $currentDate, $user)
    {
        $newAttendance = new Attendance([
            'date' => $currentDate,
        ]);
        $newAttendance->state()->associate(State::firstWhere('code', State::ACTIVE));
        $newAttendance->institution()->associate(Institution::findOrFail($institutionId));
        $newAttendance->attendanceable()->associate($user);
        $newAttendance->save();
        return $newAttendance;
    }

    private function createWorkday($user, $dataWorkday, $attendance)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $workday = new Workday([
            'start_time' => $dataWorkday['start_time'],
            'description' => $dataWorkday['description']
        ]);
        $workday->attendance()->associate($attendance);
        $workday->type()->associate(Catalogue::where('code', $dataWorkday['type']['code'])->where('type', $catalogues['workday']['type']['type'])->first());
        $workday->state()->associate(State::firstWhere('code', State::ACTIVE));
        $workday->save();
        $observation = new Observation([
            'old_values' => null,
            'new_values' => 'Hora inicio: ' . $dataWorkday['start_time'],
            'description' => $dataWorkday['observation']
        ]);

        $observation->state()->associate(State::firstWhere('code', State::ACTIVE));
        $observation->user()->associate($user);
        $observation->observationable()->associate($workday);
        $observation->save();
        return $workday;
    }

    private function calculateDuration($startTime, $endTime)
    {
        $startHour = substr($startTime, 0, 2);
        $startMinute = substr($startTime, 3, 2);
        if (strlen($startTime) < 6) {
            $startSecond = '0';
        } else {
            $startSecond = substr($startTime, 6, 2);
        }

        $endHour = substr($endTime, 0, 2);
        $endMinute = substr($endTime, 3, 2);
        if (strlen($endTime) < 6) {
            $endSecond = '0';
        } else {
            $endSecond = substr($endTime, 6, 2);
        }

        $endDate = Carbon::create(1990, 12, 04, $endHour, $endMinute, $endSecond);

        $durationFormat = $startHour . ' hours ' . $startMinute . ' minutes ' . $startSecond . ' seconds';
        return $endDate->sub($durationFormat)->format('H:i:s');
    }

    private function createOrUpdateTask($user, $datTask, $attendance)
    {
        $task = $attendance->tasks()->where('type_id', $datTask['type']['id'])->first();

        if (!$task) {
            $task = new Task([
                'percentage_advance' => $datTask['percentage_advance'],
                'description' => $datTask['description'],
            ]);
        } else {
            $task->update([
                'percentage_advance' => $datTask['percentage_advance'],
                'description' => $datTask['description'],
            ]);
        }

        $task->attendance()->associate($attendance);
        $task->type()->associate(Catalogue::findOrFail($datTask['type']['id']));
        $task->state()->associate(State::firstWhere('code', State::ACTIVE));
        $task->save();

        $observation = new Observation([
            'old_values' => null,
            'new_values' => Catalogue::findOrFail($datTask['type']['id'])->name,
            'description' => $datTask['observation']
        ]);

        $observation->state()->associate(State::firstWhere('code', State::ACTIVE));
        $observation->user()->associate($user);
        $observation->observationable()->associate($task);
        $observation->save();
        return $task;
    }

    public function reportAttendances(Request $request)
    {
//        $users = User::with(['institutions' => function ($institutions) use ($request) {
//            $institutions->where('institutions.id', $request->institution_id);
//        }])->get();
//        $start_13 = (new Carbon('2020-12-01'))->subDays(13);
//        $end_13 = (new Carbon('2020-12-01'))->subDays(1);
//        $start_18 = (new Carbon('2020-12-01'));
//        $end_18 = (new Carbon('2020-12-01'))->addDays(17);
//        $weekends13 = 0;
//        while ($start_13 <= $end_13) {
//            if ($start_13->format('l') == 'Saturday' || $start_13->format('l') == 'Sunday') {
//                $weekends13++;
//            }
//            $start_13->modify("+1 days");
//        }
//
//        $weekends18 = 0;
//        while ($start_18 <= $end_18) {
//            if ($start_18->format('l') == 'Saturday' || $start_18->format('l') == 'Sunday') {
//                $weekends18++;
//            }
//            $start_18->modify("+1 days");
//        }
//
//        $start_13 = (new Carbon('2020-12-01'))->subDays(13);
//        $end_13 = (new Carbon('2020-12-01'))->subDays(1);
//        $start_18 = (new Carbon('2020-12-01'));
//        $end_18 = (new Carbon('2020-12-01'))->addDays(17);
//
//        $reports = array();
//        foreach ($users as $user) {
//            if (sizeof($user->institutions) > 0) {
//                $attendances13 = $user->attendances()->with(['workdays' => function ($workdays) {
//                    $workdays->with('type');
//                }])
//                    ->whereBetween('date', [$start_13, $end_13])
//                    ->get();
//                foreach ($attendances13 as $attendance) {
//                    foreach ($attendance->workdays as $workday) {
//                        $this->calculateTotalDuration($workday);
//                    }
//                }
//
//                $totalHours13 = $this->totalHours;
//                $totalMinutes13 = $this->totalMinutes;
//                $totalSeconds13 = $this->totalSeconds;
//
//                if ($totalSeconds13 >= 30) {
//                    $totalMinutes13++;
//                }
//                if ($totalMinutes13 >= 30) {
//                    $totalHours13++;
//                }
//
//                $attendances18 = $user->attendances()->with(['workdays' => function ($workdays) {
//                    $workdays->with('type');
//                }])
//                    ->whereBetween('date', [$start_18, $end_18])
//                    ->get();
//                $this->totalSeconds = 0;
//                $this->totalMinutes = 0;
//                $this->totalHours = 0;
//                foreach ($attendances18 as $attendance) {
//                    foreach ($attendance->workdays as $workday) {
//                        $this->calculateTotalDuration($workday);
//                    }
//                }
//                $totalHours18 = $this->totalHours;
//                $totalMinutes18 = $this->totalMinutes;
//                $totalSeconds18 = $this->totalSeconds;
//                echo $totalHours18 . ' || ';
//
//                if ($totalSeconds18 >= 30) {
//                    $totalMinutes18++;
//                }
//                if ($totalMinutes18 >= 30) {
//                    $totalHours18++;
//                }
//
//                if (sizeof($attendances13) > 0 || sizeof($attendances18) > 0) {
//                    array_push($reports,
//                        [
//                            'month' => $start_18->format('F'),
//                            'user' => $user,
//                            'institution' => $user->institutions[0]->denomination . ' ' . $user->institutions[0]->name,
//                            'days13' => ($totalHours13 / 8) + $weekends13,
//                            'days18' => ($totalHours18 / 8) + $weekends18
//                        ]);
//                }
//                $this->totalSeconds = 0;
//                $this->totalMinutes = 0;
//                $this->totalHours = 0;
//            }
//        }
//        return $reports;

        return (new AttendancesExport)
            ->institutionId((int)$request->institution_id)
            ->date($request->date)
            ->download('attendances.xlsx');
    }

    public function reportTasks(Request $request)
    {
//        $institution = Institution::findOrFail(2);
//        $users = User::whereHas('institutions', function ($institutions) use ($institution) {
//            $institutions->where('institutions.id', $institution->id);
//        })->has('teacher')->get();
//
//        $reports = array();
//        foreach ($users as $user) {
//            $attendances = $user->attendances()->with(['tasks' => function ($workdays) {
//                $workdays->with(['type' => function ($type) {
//                    $type->with(['parent' => function ($parent) {
//                        $parent->orderBy('name');
//                    }]);
//                }]);
//            }])
//                ->whereBetween('date', [$request->start_date, $request->end_date])
//                ->orderBy('date')
//                ->get();
//            $tasks = array();
//            $processes = array();
//            foreach ($attendances as $attendance) {
//                foreach ($attendance->tasks as $task) {
//                    array_push($tasks, $task);
//                }
//            }
//
//
//            //if (sizeof($attendances) > 0) {
//            array_push($reports,
//                [
//                    'attendances' => $attendances,
//                    'user' => $user,
//                    'tasks' => $tasks,
//                ]);
//            //}
//            $task = array();
//        }
////        return $reports;

        return (new TasksExport())
            ->institutionId((int)$request->institution_id)
            ->startDate($request->start_date)
            ->endDate($request->end_date)
            ->download('tasks.xlsx');
    }

    private $totalSeconds = 0;
    private $totalMinutes = 0;
    private $totalHours = 0;

    private function calculateTotalDuration($workday)
    {
        $hour = substr($workday->duration, 0, 2);
        $minute = substr($workday->duration, 3, 2);
        $second = substr($workday->duration, 6, 2);

        if ($workday->type->code === 'WORK') {
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

        if ($workday->type->code === 'LUNCH') {
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

            if (($this->totalHours - (int)$hour) >= 0) {
                $this->totalHours -= (int)$hour;
            }
        }
    }
}
