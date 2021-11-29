<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const SECRET_KEY = "4h7t7vtn7vt";

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'token'
    ];

    public static function getPasswordHash($password)
    {
        return md5(User::SECRET_KEY . $password);
    }

    public static function authByCredentials($email, $password)
    {
        $user = self::where([
            'email' => $email,
            'password' => self::getPasswordHash($password)
        ])->first();
        if (empty($user)) {
            //todo Создать отдельный класс исключения
            throw new \Exception('Authorization failed');
        }
        return $user;
    }

    public function timezone()
    {
        return $this->belongsTo('App\Models\Timezone');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
}
