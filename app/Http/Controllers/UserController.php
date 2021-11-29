<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistered;
use App\Models\Timezone;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:255|confirmed'
        ]);
        $user = new User;
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();
        try {
            //Реально письма не отправляются, нужно настроить конфиг для подключения к почтовому серверу
            Mail::to($user->email)->send(new UserRegistered($user));
        } catch (\Exception $e) {
            $user->mail_error = $e->getMessage();
        }

        return $user;
    }

    public function auth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255'
        ]);
        $email = $request->input('email');
        $password = $request->input('password');
        try {
            $user = User::authByCredentials($email, $password);
            //При каждом auth генерируется новый токен, в дальнейшем можно сделать ради безопасности ttl для них, по истечении которого нужен новый auth
            $user->token = Str::uuid();
            $user->save();
        } catch (\Exception $e) {
            return response(json_encode($e->getMessage()))->setStatusCode(401);
        }
        return $user->token;
    }

    public function settings(Request $request)
    {
        //language, timezone - строки, но в users пишем соответствующие id
        $this->validate($request, [
            'language' => 'exists:languages,name',
            'timezone' => 'exists:timezones,name'
        ]);
        $language = $request->input('language');
        $timezone = $request->input('timezone');
        //user загружается из middleware аутентификации
        $user = $request->input('user');
        if (!empty($language)) {
            $user->language_id = Language::where('name', $language)->first()->id;
        }
        if (!empty($timezone)) {
            $user->timezone_id = Timezone::where('name', $timezone)->first()->id;
        }
        $user->save();
        return $user;
    }
}
