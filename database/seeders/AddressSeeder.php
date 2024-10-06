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
        for ($this->i; $this->i < 40; $this->i++) {
            $index = $this->i + 1;

            if ($this->i < 30) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($this->userId)->isActive()->newRecord()->create()->save();
            } elseif ($this->i < 33) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($this->userId)->isNotActive()->newRecord()->create()->save();
            } elseif ($this->i < 36) {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($this->userId)->isActive()->newRecord()->softDeleted()->create()->save();
            } else {
                Address::factory()->street($index)->city($index)->province($index)->country($index)->postalCode()->contactId($this->userId)->isNotActive()->newRecord()->softDeleted()->create()->save();
            }

            if ($index % 3 == 0) {
                $this->userId++;
            }
        }
    }
}
