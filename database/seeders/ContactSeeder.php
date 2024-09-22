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
        for ($this->i; $this->i < 20; $this->i++) {
            $index = $this->i + 1;

            if ($this->i < 10) {
                Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isActive()->newRecord()->create()->save();
            } elseif ($this->i < 13) {
                Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isNotActive()->newRecord()->create()->save();
            } elseif ($this->i < 16) {
                Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isActive()->newRecord()->softDeleted()->create()->save();
            } else {
                Contact::factory()->firstName($index)->lastName($index)->userId($this->userId)->email($index)->phoneIndonesia()->isNotActive()->newRecord()->softDeleted()->create()->save();
            }

            if ($index % 3 == 0) {
                $this->userId++;
            }
        }
    }
}
