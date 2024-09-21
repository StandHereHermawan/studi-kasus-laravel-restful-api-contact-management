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
}
