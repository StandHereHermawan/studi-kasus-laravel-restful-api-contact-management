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

    public function testGetContactSuccess(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => $user->token,
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => $contact->first_name,
                    "last_name" => $contact->last_name,
                    "email" => $contact->email,
                    "phone" => $contact->phone,
                ],
            ]);
    }

    public function testGetContactNotFound(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->get('/api/contacts/' . $contact->id + 20, [
            "Authorization" => $user->token,
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Contact not found."
                    ],
                ],
            ]);
    }

    public function testGetContactFromOtherUser(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user1 = User::where('id', '=', 2)->first();
        self::assertNotNull($user1, );
        self::assertNotNull($user1->token, );
        Log::info(json_encode($user1));

        $user2 = User::where('id', '=', 3)->first();
        self::assertNotNull($user2, );
        self::assertNotNull($user2->token, );
        Log::info(json_encode($user2));

        $contact = Contact::where('user_id', '=', $user1->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => $user2->token,
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Contact not found."
                    ],
                ],
            ]);
    }

    public function testGetContactURINotFound(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 2)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $this->get('/api/contacts/abcd', [
            "Authorization" => $user->token,
        ])->withException(new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException());
    }
}
