<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $index = $i + 1;

            if ($i < 1) {
                User::factory()->name($index)->username($index)->password()->isActive()->newRecord()->create()->save();
            } else if ($i < 7) {
                User::factory()->name($index)->username($index)->password()->isActive()->newRecord()->activeToken()->create()->save();
            } else if ($i < 11) {
                User::factory()->name($index)->username($index)->password()->isNotActive()->newRecord()->create()->save();
            } else if ($i < 13) {
                User::factory()->name($index)->username($index)->password()->isActive()->newRecord()->activeToken()->softDeleted()->create()->save();
            } else {
                User::factory()->name($index)->username($index)->password()->isNotActive()->newRecord()->activeToken()->softDeleted()->create()->save();
            }
        }
    }
}
