<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Authentication\Role;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use App\Models\Attendance\Task;
use App\Models\Ignug\Teacher;
use App\Models\Authentication\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $data = $request->json()->all();
        $dataTask = $data['task'];

        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->firstWhere('date', $currentDate);

        if ($attendance) {
            $this->createOrUpdateTask($dataTask, $attendance);
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
                $workdays->with('type')->where('state_id', State::firstWhere('code', State::ACTIVE)->id)->orderBy('start_time');
            }])->with(['tasks' => function ($tasks) {
                $tasks->with(['type' => function ($type) {
                    $type->with(['parent' => function ($parent) {
                        $parent->orderBy('name');
                    }]);
                }])->where('state_id', State::firstWhere('code', State::ACTIVE)->id);
            }])
                ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
                ->where('date', Carbon::now())
                ->first(),
            'msg' => [
                'summary' => 'Success',
                'detail' => 'Se guardó correctamente la actividad',
                'code' => '201',
            ]], 201);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->state()->associate(State::firstWhere('code', State::DELETED));
        $task->save();
        $tasks = Task::where('attendance_id', $task['attendance_id'])
            ->where('state_id', State::firstWhere('code', State::DELETED)->id)
            ->get();
        return response()->json([
            'data' => $tasks,
            'msg' => [
                'summary' => 'Se eliminó correctamente',
                'detail' => 'Tu actividad fue eliminada',
                'code' => '201',
            ]], 201);
    }

    public function createOrUpdateTask($datTask, $attendance)
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
        return $task;
    }

    public function getTotalProcesses(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $role = Role::findOrFail($request->role_id);
        $user = User::findOrFail($request->user_id);
        $processes = $role->catalogues()->where('type', $catalogues['task']['process']['type'])->orderBy('name')->get();
        $attendances = $user->attendances()
            ->with(['workdays' => function ($workdays) {
                $workdays->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
                    ->with('type');
            }])
            ->with(['tasks' => function ($tasks) {
                $tasks->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
                    ->with(['type' => function ($type) {
                        $type->with('parent');
                    }]);
            }])
            ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
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
        $reponse = ['data' => $data, 'labels' => $labels, 'background_color' => $backgroundColor];

        return response()->json([
            'data' => $reponse,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }
}
