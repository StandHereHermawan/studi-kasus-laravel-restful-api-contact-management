<?php

namespace Tests\Feature\Model;

use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testContacts(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class]);

        $contacts = Contact::all();
        self::assertNotNull($contacts);

        $contacts->each(function ($contact): void {
            self::assertNotNull($contact, );
            Log::info(json_encode($contact));

            $contact->user()->each(function ($user): void {
                self::assertNotNull($user, );
                Log::info(json_encode($user));
            });
        });
    }

    public function testCreateContactSuccess(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        $userToken = $user->token;

        $this->post('/api/contacts', [
            "first_name" => 'Hilmi',
            "last_name" => 'Akbar Muharrom',
            "email" => 'noir2005@proton.com',
            "phone" => "+62-851-7057-8322",
        ], [
            "Authorization" => $userToken,
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "first_name" => 'Hilmi',
                    "last_name" => 'Akbar Muharrom',
                    "email" => 'noir2005@proton.com',
                    "phone" => "+62-851-7057-8322",
                ],
            ]);
    }

    public function testCreateContactValidationError(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        $userToken = $user->token;

        $this->post('/api/contacts', [
            "first_name" => '',
            "last_name" => 'Akbar Muharrom',
            "email" => 'noir2005',
            "phone" => "+62-851-7057-8322",
        ], [
            "Authorization" => $userToken,
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "first_name" => [
                        0 => 'The first name field is required.',
                    ],
                    "email" => [
                        0 => 'The email field must be a valid email address.',
                    ],
                ],
            ]);
    }

    public function testCreateContactUnauthorized(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        $userToken = $user->token;

        $this->post('/api/contacts', [
            "first_name" => '',
            "last_name" => 'Akbar Muharrom',
            "email" => 'noir2005',
            "phone" => "+62-851-7057-8322",
        ], [
            // "Authorization" => $userToken,
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ],
                ],
            ]);
    }
}
