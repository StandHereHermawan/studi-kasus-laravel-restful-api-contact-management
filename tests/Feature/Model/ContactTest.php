<?php

namespace Tests\Feature\Model;

use App\Models\Contact;
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
            self::assertNotNull($contact,);
            Log::info(json_encode($contact));

            $contact->user()->each(function ($user): void {
                self::assertNotNull($user,);
                Log::info(json_encode($user));
            });
        });
    }
}
