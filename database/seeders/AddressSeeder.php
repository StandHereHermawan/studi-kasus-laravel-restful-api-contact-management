<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    private $i = 0;

    private int $userId = 1;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($this->i; $this->i < 20; $this->i++) {
            $index = $this->i + 1;

            if ($this->i < 10) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($index)->isActive()->newRecord()->create()->save();
            } elseif ($this->i < 13) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($index)->isNotActive()->newRecord()->create()->save();
            } elseif ($this->i < 16) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($index)->isActive()->newRecord()->softDeleted()->create()->save();
            } else {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($index)->isNotActive()->newRecord()->softDeleted()->create()->save();
            }

            if ($index % 3 == 0) {
                $this->userId++;
            }
        }
    }
}
