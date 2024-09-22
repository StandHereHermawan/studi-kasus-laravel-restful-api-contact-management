<?php

namespace Tests\Feature\Model;

use App\Models\Address;
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
            self::assertNotNull($address,);
            Log::info(json_encode($address));

            $address->contact()->each(function ($contact): void {
                self::assertNotNull($contact,);
                Log::info(json_encode($contact));
            });
        });
    }
}
