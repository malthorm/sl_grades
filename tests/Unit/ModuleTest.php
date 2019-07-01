<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_courses()
    {
        $module = factory('App\Module')->create();

        $this->assertInstanceOf(Collection::class, $module->courses);
    }
}
