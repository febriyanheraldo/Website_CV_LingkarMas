<?php

namespace Tests\Feature;

use Tests\TestCase;

class CekDatabaseTest extends TestCase
{
    public function test_test_memakai_sqlite_bukan_mysql(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
    }
}
