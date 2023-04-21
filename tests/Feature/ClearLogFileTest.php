<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClearLogFileTest extends TestCase
{
    public function test_no_errors_returned()
    {
        $this->artisan('log:clear')
            ->assertExitCode(0);
    }
}
