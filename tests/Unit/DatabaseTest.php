<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models;

class DatabaseTest extends TestCase
{
    public function test_fetch()
    {
        $school = Models\School::first();
        $this->assertNotNull($school);

        $this->assertTrue($school->campuses->count() > 0);
        $this->assertTrue($school->colleges->count() > 0);

        $program = Models\Program::all()->random();
        $this->assertNotNull($program->college);
        $this->assertNotNull($program->college->campus);
        $this->assertNotNull($program->college->campus->school);
        $this->assertNotNull($program->college->school);
    }
}
