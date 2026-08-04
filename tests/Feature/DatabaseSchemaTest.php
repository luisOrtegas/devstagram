<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_functionality_tables_and_columns_exist(): void
    {
        $this->assertTrue(Schema::hasTable('notifications'));
        $this->assertTrue(Schema::hasTable('post_mentions'));
        $this->assertTrue(Schema::hasColumns('users', [
            'telefono',
            'direccion',
            'biografia',
        ]));
    }
}
