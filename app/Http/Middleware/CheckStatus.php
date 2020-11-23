<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Authentication\Status;
use App\Models\Ignug\State;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->state_id === State::firstWhere('code', State::DELETED)->id) {
            return response()->json([
                'data' => State::firstWhere('code', State::DELETED)->id,
                'msg' => [
                    'summary' => 'Tu usuario se encuentra Eliminado!',
                    'detail' => 'Comunicate con el administrador',
                    'code' => '4030'
                ]
            ], 404);
        }
        if ($request->user()->status_id === Status::where('code', Status::INACTIVE)->first()->id) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Tu usuario se encuentra Inactivo',
                    'detail' => 'Comunicate con el administrador',
                    'code' => '4030'
                ]
            ], 403);
        }

        if ($request->user()->status_id === Status::where('code', Status::LOCKED)->first()->id) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Tu usuario se encuentra Bloqueado',
                    'detail' => 'Comunicate con el administrador',
                    'code' => '423'
                ]
            ], 423);
        }
        return $next($request);
    }
}
