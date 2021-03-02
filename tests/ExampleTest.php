<?php

namespace A1\Channel\Tests;

use A1\Channel\A1ServiceProvider;
use Orchestra\Testbench\TestCase;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [A1ServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
