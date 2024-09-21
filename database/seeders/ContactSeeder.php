<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    private $i = 0;

    private int $userId = 1;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($this->i; $this->i < 10; $this->i++) {
            $index = $this->i + 1;
            Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isActive()->newRecord()->create()->save();
            if ($this->i % 3 == 0) {
                $this->userId++;
            }
        }

        for ($this->i; $this->i < 13; $this->i++) {
            $index = $this->i + 1;
            Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isActive()->newRecord()->softDeleted()->create()->save();
        }
    }
}
