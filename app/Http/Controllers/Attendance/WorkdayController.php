<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Ignug\State;
use App\Http\Controllers\Controller;
use App\Models\Ignug\Catalogue;
use App\Models\Attendance\Workday;
use App\Models\Attendance\Attendance;
use App\Models\Authentication\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkdayController extends Controller
{
    public function update(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d | H:i:s');
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];
        $workday = Workday::findOrFail($dataWorkday['id']);
        if (!$workday->observations) {
            $workday->observations = array();
        }
        $user = User::findOrFail($request->user_id);
        $dataWorkday['observations'][0] = 'Motivo: ' . $dataWorkday['observations'][0];
        if ($dataWorkday['start_time'] != $workday->start_time) {
            array_push($dataWorkday['observations'], 'Hora inicio anterior: ' . $workday->start_time);
            array_push($dataWorkday['observations'], 'Hora inicio nuevo: ' . $dataWorkday['start_time']);
        }
        if ($dataWorkday['end_time'] != $workday->end_time) {
            array_push($dataWorkday['observations'], 'Hora fin anterior: ' . $workday->end_time);
            array_push($dataWorkday['observations'], 'Hora fin nuevo: ' . $dataWorkday['end_time']);
        }
        array_push($dataWorkday['observations'], 'Modificado por: ' . $user->identification
            . ' ' . $user->first_lastname . ' ' . $user->second_lastname
            . ' ' . $user->first_name . ' ' . $user->second_name
            . ' (' . $currentDate . ')');
        $observations = array($dataWorkday['observations']);

        if (!Validator::make($dataWorkday, ['start_time' => 'required', 'end_time' => 'required'])->fails()) {
            $workday->update([
                'start_time' => $dataWorkday['start_time'],
                'end_time' => $dataWorkday['end_time'],
                'duration' => $this->calculateDuration($dataWorkday['start_time'], $dataWorkday['end_time']),
                'observations' => array_merge($workday->observations, $observations)
            ]);
        } else if (Validator::make($dataWorkday, ['start_time' => 'required'])->fails()) {
            $workday->update([
                'end_time' => $dataWorkday['end_time'],
                'observations' => array_merge($workday->observations, $observations)
            ]);
            if ($workday->start_time) {
                $workday->update([
                    'duration' => $this->calculateDuration($workday->start_time, $workday->end_time)]);
            }
        } else if (Validator::make($dataWorkday, ['end_time' => 'required'])->fails()) {
            $workday->update([
                'start_time' => $dataWorkday['start_time'],
                'observations' => array_merge($workday->observations, $observations)
            ]);
            if ($workday->end_time) {
                $workday->update(['duration' => $this->calculateDuration($workday->start_time, $workday->end_time)]);
            }
        }

        $state = State::firstWhere('code', State::ACTIVE);
        $workdays = $state->workdays()
            ->where('attendance_id', $workday['attendance_id'])
            ->orderBy('start_time')
            ->get();
        return response()->json([
            'data' => $workdays,
            'msg' => [
                'summary' => 'update',
                'detail' => '',
                'code' => '201',
            ]], 201);
    }

    public function destroy($id)
    {
        $workday = Workday::findOrFail($id);
        $state = State::firstWhere('code', State::DELETED);
        $workday->state()->associate($state);
        $workday->save();
        $workdays = Workday::where('attendance_id', $workday['attendance_id'])->orderBy('start_time')
            ->with(['state' => function ($state) {
                $state->where('code', State::ACTIVE);
            }])
            ->get();
        return response()->json([
            'data' => $workdays,
            'msg' => [
                'summary' => 'deleted',
                'detail' => '',
                'code' => '201',
            ]], 201);
    }

    public function startDay(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i:s');

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

        $attendance = $user->attendances()->firstWhere('date', $currentDate);
        if (!$attendance) {
            $attendance = $this->createAttendance($currentDate, $user);
        }
        if ($dataWorkday['type']['code'] === $catalogues['workday']['type']['work']) {
            $works = $attendance->workdays()
                ->where('type_id', Catalogue::where('code',
                    $catalogues['workday']['type']['work'])->where('type', $catalogues['workday']['type']['type'])->first()->id)
                ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
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
                ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
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
        $dataWorkday['start_time'] = $currentTime;
        $this->createWorkday($dataWorkday, $attendance);

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
                'summary' => 'success',
                'detail' => '',
                'code' => '201',
            ]], 201);
    }

    public function endDay(Request $request)
    {
        $currentTime = Carbon::now()->format('H:i:s');
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];

        $workday = Workday::findOrFail($dataWorkday['id']);

        $attendance = $workday->attendance()->first();
        if (sizeof($attendance->tasks()->where('state_id', State::firstWhere('code', State::ACTIVE)->id)->get()) === 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Debe registrar una actividad por lo menos',
                    'detail' => 'Intente de nuevo',
                    'code' => '404',
                ]], 404);
        }


        if ($workday !== null && $workday->end_time === null) {
            $workday->update([
                'end_time' => $currentTime,
                'duration' => $this->calculateDuration($workday->start_time->format('H:i:s'), $currentTime),
                'observations' => $dataWorkday['observations'] != null ? $dataWorkday['observations'] : null
            ]);
        }

        $workdays = Workday::where('attendance_id', $workday['attendance_id'])
            ->with('type')
            ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
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

    private function createAttendance($currentDate, $user)
    {
        $newAttendance = new Attendance([
            'date' => $currentDate,
        ]);
        $newAttendance->state()->associate(State::firstWhere('code', State::ACTIVE));
        $newAttendance->attendanceable()->associate($user);
        $newAttendance->save();
        return $newAttendance;
    }

    private function createWorkday($dataWorkday, $attendance)
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
        return $workday;
    }

    private function calculateDuration($startTime, $endTime)
    {
        $startHour = substr($startTime, 0, 2);
        $startMinute = substr($startTime, 3, 2);
        $startSecond = substr($startTime, 6, 2);

        $endHour = substr($endTime, 0, 2);
        $endMinute = substr($endTime, 3, 2);
        $endSecond = substr($endTime, 6, 2);

        $endDate = Carbon::create(1990, 12, 04, $endHour, $endMinute, $endSecond);

        $durationFormat = $startHour . ' hours ' . $startMinute . ' minutes ' . $startSecond . ' seconds';
        return $endDate->sub($durationFormat)->format('H:i:s');
    }
}
