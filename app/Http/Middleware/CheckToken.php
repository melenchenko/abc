<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class CheckToken
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
        $token = $request->header(Controller::TOKEN_HEADER_KEY);
        if (empty($token)) {
            return response(json_encode('Empty token'))->setStatusCode(422);
        }
        $user = User::where('token', $token)->first();
        if (empty($user)) {
            return response(json_encode('Wrong token'))->setStatusCode(401);
        } else {
            //Сохраняем найденного пользователя в $request->input
            $request->merge(['user' => $user]);
        }
        return $next($request);
    }
}
