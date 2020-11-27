<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\Task;
use App\Models\Attendance\Workday;
use App\Models\Authentication\Role;
use App\Models\Authentication\User;
use App\Models\Ignug\Institution;
use App\Models\Ignug\Observation;
use App\Models\Ignug\State;
use App\Models\Ignug\Catalogue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $attendance = $user->attendances()->where('date', $currentDate)->where('institution_id', $request->institution_id)->first();

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

    public function download()
    {
        $data = [
            'titulo' => 'Styde.net'
        ];

        $pdf = \PDF::loadView('vista-pdf', $data);

        return $pdf->download('archivo.pdf');
    }
}
