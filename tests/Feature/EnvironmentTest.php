<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Safety / sanity checks for the test environment itself.
 *
 * The most important guarantee: tests must NEVER run against the real
 * application database (RefreshDatabase drops every table). These assertions
 * fail loudly if the isolated test database is ever misconfigured.
 *
 * Note: deliberately does NOT use RefreshDatabase, so it is safe to run even
 * if something is wrong.
 */
class EnvironmentTest extends TestCase
{
    #[Test]
    public function it_runs_in_the_testing_environment(): void
    {
        $this->assertSame('testing', app()->environment());
    }

    #[Test]
    public function it_uses_an_isolated_test_database(): void
    {
        $database = DB::connection()->getDatabaseName();

        $this->assertSame(
            'empapy_caffee2_test',
            $database,
            'Tests must run against the isolated test database, not the real one.'
        );

        // Belt and braces: never the production/local database name.
        $this->assertNotSame('empapy_caffee2', $database);
    }
}
