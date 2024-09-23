<?php

namespace Tests\Feature\Model;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testUser(): void
    {
        self::seed([UserSeeder::class]);

        $users = User::all();
        self::assertNotNull($users);

        $users->each(function ($user): void {
            self::assertNotNull($user);
            Log::info(json_encode($user));
        });
    }

    public function testRegisterSuccess(): void
    {
        $response = $this->post('/api/users', [
            "username" => 'noir2005',
            "password" => 'Rahasia@2005',
            "name" => 'Hilmi Akbar Muharrom',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "username" => 'noir2005',
                    "name" => 'Hilmi Akbar Muharrom',
                ],
            ]);

        self::assertNotNull($response,);
        Log::info(json_encode($response));
        Log::info(json_encode($response->json()));
    }

    public function testRegisterFailedBlank(): void
    {
        $response = $this->post('/api/users', [
            "username" =>   '',
            "password" =>   '',
            "name" =>       '',
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "name" => [
                        "The name field is required."
                    ],
                ],
            ]);

        self::assertNotNull($response,);
        Log::info(json_encode($response));
        Log::info(json_encode($response->json()));
    }

    public function testRegisterUsernameAlreadyExist(): void
    {
        $response = $this->post('/api/users', [
            "username" => 'noir2005',
            "password" => 'Rahasia@2005',
            "name" => 'Hilmi Akbar Muharrom',
        ]);
        self::assertNotNull($response,);

        $response = $this->post('/api/users', [
            "username" => 'noir2005',
            "password" => 'Rahasia@2005',
            "name" => 'Hilmi Akbar Muharrom',
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "username" => [],
                ],
            ]);

        self::assertNotNull($response,);
        Log::info(json_encode($response));
        Log::info(json_encode($response->json()));
    }

    public function testRegisterUsernameCantSameAsName(): void
    {
        $response = $this->post('/api/users', [
            "username" => 'noir2005',
            "password" => 'Rahasia@2005',
            "name" => 'noir2005',
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "name" => [],
                ],
            ]);

        self::assertNotNull($response,);
        Log::info(json_encode($response));
        Log::info(json_encode($response->json()));
    }

    public function testLoginSuccess(): void
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            "username" => 'SAMPLE-USERNAME-1',
            "password" => 'Rahasia@12',
        ])->assertStatus(200)->assertJson([
            "data" => [
                "username" => 'SAMPLE-USERNAME-1',
                "name" => "SAMPLE-NAME-1",
            ],
        ]);

        $user = User::where('username', 'SAMPLE-USERNAME-1')->first();
        self::assertNotNull($user->token,);

        Log::info(json_encode($user));
    }

    public function testLoginUsernameNotFound(): void
    {
        $this->post('/api/users/login', [
            "username" => 'SAMPLE-USERNAME-1',
            "password" => 'Rahasia@12',
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "Username or password wrong."
                ],
            ],
        ]);
    }

    public function testLoginPasswordWrong(): void
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            "username" => 'SAMPLE-USERNAME-1',
            "password" => 'Rahasia@123',
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "Username or password wrong."
                ],
            ],
        ]);
    }
}
