<?php

namespace Tests\Feature\Model;

use App\Models\Contact;
use App\Models\User;

use Database\Seeders\ContactCollectionSearchSeeder;
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

    public function testUpdateContactSuccess(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "Hilmi",
            "last_name" => "Akbar Muharrom",
            "email" => "noir2004@proton.com",
            "phone" => "+62-851-7500-3822",
        ], [
            "Authorization" => $user->token,
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "Hilmi",
                    "last_name" => "Akbar Muharrom",
                    "email" => "noir2004@proton.com",
                    "phone" => "+62-851-7500-3822",
                ],
            ]);

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));
    }

    public function testUpdateContactValidationError(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "",
            "last_name" => "Akbar Muharrom",
            "email" => "noir2004@proton.com",
            "phone" => "+62-851-7500-3822",
        ], [
            "Authorization" => $user->token,
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "first_name" => [
                        "The first name field is required."
                    ],
                ],
            ]);
    }

    public function testUpdateContactNotAuthorized(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "Hilmi",
            "last_name" => "Akbar Muharrom",
            "email" => "noir2004@proton.com",
            "phone" => "+62-851-7500-3822",
        ], [
            "Authorization" => 'salah',
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ],
                ],
            ]);
    }

    public function testDeleteSuccess(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->limit(1)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->delete(uri: '/api/contacts/' . $contact->id, data: [], headers: [
            "Authorization" => $user->token,
        ])->assertStatus(200)
            ->assertJson([
                "data" => true,
            ]);

        $contact2 = Contact::where('id', '=', $contact->id)->limit(1)->first();
        self::assertNull($contact2, );
    }

    public function testDeleteIdNotFound(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->limit(1)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $this->delete(uri: '/api/contacts/' . $contact->id + 7, data: [], headers: [
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

    public function testSearchContactByFirstName(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?name=FIRST', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(12, $response['meta']['total']);
    }

    public function testSearchContactByLastName(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?name=LAST', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(12, $response['meta']['total']);
    }

    public function testSearchContactByEmail(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?email=sample', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(12, $response['meta']['total']);
    }

    public function testSearchContactByPhone(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?phone=+62', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(12, $response['meta']['total']);
    }

    public function testSearchContactNotFound(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?phone=tidakada', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }

    public function testSearchContactWithPage(): void
    {
        self::seed([UserSeeder::class, ContactCollectionSearchSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $response = $this->get('/api/contacts?size=4&page=2', [
            "Authorization" => $user->token,
        ])
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(4, count($response['data']));
        self::assertEquals(12, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
