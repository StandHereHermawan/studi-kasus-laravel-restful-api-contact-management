<?php

namespace Tests\Feature\Model;

use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testAddresses(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $addresses = Address::all();
        self::assertNotNull($addresses);

        $addresses->each(function ($address): void {
            Log::info("====================");
            self::assertNotNull($address, );
            Log::info(json_encode($address));

            $address->contact()->each(function ($contact): void {
                self::assertNotNull($contact, );
                Log::info(json_encode($contact));
            });
        });
    }

    public function testCreateSuccess(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));

        $dataAddress = [
            "street" => 'SAMPLE-STREET',
            "city" => 'SAMPLE-CITY',
            "province" => 'SAMPLE-PROVINCE',
            "country" => 'SAMPLE-COUNTRY',
            "postal_code" => '40394',
        ];

        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            /*
             | [
             |     "street" => 'SAMPLE-STREET',
             |     "city" => 'SAMPLE-CITY',
             |     "province" => 'SAMPLE-PROVINCE',
             |     "country" => 'SAMPLE-COUNTRY',
             |     "postal_code" => '40394',
             | ],
            */
            $dataAddress,
            [
                "Authorization" => $user->token,
            ]
        )->assertStatus(201)
            ->assertJson([
                "data" => $dataAddress
                /*
                | [
                |     "street" => 'SAMPLE-STREET',
                |     "city" => 'SAMPLE-CITY',
                |     "province" => 'SAMPLE-PROVINCE',
                |     "country" => 'SAMPLE-COUNTRY',
                |     "postal_code" => '40394',
                | ],
                */
                ,
            ]);

        $address = Address::where('contact_id', '=', $contact->id)->where('postal_code', '=', $dataAddress['postal_code'])->first();
        self::assertNotNull($address, );
        Log::info(json_encode($address));
    }

    public function testCreateValidationError(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));


        $dataAddress = [
            "street" => 'SAMPLE-STREET',
            "city" => 'SAMPLE-CITY',
            "province" => 'SAMPLE-PROVINCE',
            // "country" => 'SAMPLE-COUNTRY',
            "postal_code" => '40394',
        ];

        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            $dataAddress,
            [
                "Authorization" => $user->token,
            ]
        )->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "country" => ['The country field is required.'],
                ],
            ]);
    }

    public function testCreateContactNotFound(): void
    {
        self::seed([UserSeeder::class, ContactSeeder::class,]);

        $user = User::where('id', '=', 3)->first();
        self::assertNotNull($user, );
        self::assertNotNull($user->token, );
        Log::info(json_encode($user));

        $contact = Contact::where('user_id', '=', $user->id)->first();
        self::assertNotNull($contact, );
        Log::info(json_encode($contact));


        $dataAddress = [
            "street" => 'SAMPLE-STREET',
            "city" => 'SAMPLE-CITY',
            "province" => 'SAMPLE-PROVINCE',
            "country" => 'SAMPLE-COUNTRY',
            "postal_code" => '40394',
        ];

        $this->post(
            '/api/contacts/' . $contact->id + 20 . '/addresses',
            $dataAddress,
            [
                "Authorization" => $user->token,
            ]
        )->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        'Contact not found.'
                    ],
                ],
            ]);
    }
}
