<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Timezone;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SettingsTest extends TestCase
{
    use WithFaker;

    private $token;
    private $timezone;
    private $language;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::whereNotNull(['token', 'language_id', 'timezone_id'])->first();

        $this->token = $user->token;
        $this->language = $user->language->name;
        $this->timezone = $user->timezone->name;
    }

    public function test_settings_no_token()
    {
        $response = $this->put('/api/user/settings');

        $response->assertStatus(422);
    }

    public function test_settings_wrong_token()
    {
        $response = $this->put('/api/user/settings', [], [
            Controller::TOKEN_HEADER_KEY => $this->faker->text(50)
        ]);

        $response->assertStatus(401);
    }

    public function test_settings_empty_parameters()
    {
        $response = $this->put('/api/user/settings', [], [
            Controller::TOKEN_HEADER_KEY => $this->token
        ]);

        $response->assertStatus(200);
    }

    public function test_settings_incorrect_parameters()
    {
        $response = $this->put('/api/user/settings', [
            'language' => $this->language,
            'timezone' => $this->faker->text(50)
        ], [
            Controller::TOKEN_HEADER_KEY => $this->token
        ]);

        $response->assertStatus(422);
    }

    public function test_settings_correct()
    {
        $response = $this->put('/api/user/settings', [
            'language' => $this->language,
            'timezone' => $this->timezone
        ], [
            Controller::TOKEN_HEADER_KEY => $this->token
        ]);

        $response->assertStatus(200);
    }
}
