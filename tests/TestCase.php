<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        // DB::statement("TRUNCATE TABLE contacts");
        // DB::statement("TRUNCATE TABLE users");
        // gak bisa, kena error foreign key

        parent::setUp();
        DB::statement("DELETE FROM addresses");
        DB::statement("ALTER TABLE addresses AUTO_INCREMENT = 1;");
        DB::statement("DELETE FROM contacts");
        DB::statement("ALTER TABLE contacts AUTO_INCREMENT = 1;");
        DB::statement("DELETE FROM users");
        DB::statement("ALTER TABLE users AUTO_INCREMENT = 1;");
    }
}
