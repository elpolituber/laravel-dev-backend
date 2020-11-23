<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
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
        return $next($request);
    }
}
