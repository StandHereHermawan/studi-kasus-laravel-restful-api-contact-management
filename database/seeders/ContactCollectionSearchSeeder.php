<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactCollectionSearchSeeder extends Seeder
{
    private $startNumber = 0;
    private int $userId = 1;
    private $endIteration = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($this->startNumber; $this->startNumber < $this->endIteration; $this->startNumber++) {
            
            for ($j = 0; $j < $this->endIteration + 2; $j++) {
                $index = $j + 1;

                Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isActive()->newRecord()->create()->save();
            }

            $this->userId++;
        }
    }
}
