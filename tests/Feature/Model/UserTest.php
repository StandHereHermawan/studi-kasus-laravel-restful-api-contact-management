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
}
