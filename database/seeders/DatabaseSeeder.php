<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Timezone;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'name' => 'ru',
        ]);
        DB::table('timezones')->insert([
            'name' => 'MSK',
        ]);
        for ($i = 1; $i <= 4; $i++) {
            DB::table('languages')->insert([
                'name' => bin2hex(random_bytes(4)),
            ]);
            DB::table('timezones')->insert([
                'name' => bin2hex(random_bytes(4)),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'email' => ($i == 1 ? 'testuser' : bin2hex(random_bytes(10))) . '@gmail.com',
                'password' => User::getPasswordHash('12345678'),
                'token' => Str::uuid(),
                'language_id' => Language::orderByRaw('RAND()')->first()->id,
                'timezone_id' => Timezone::orderByRaw('RAND()')->first()->id
            ]);
        }
    }
}
