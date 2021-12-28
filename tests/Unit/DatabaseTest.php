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

    /**
     * @requires PHP >= 20.0
     */
    public function test_update_program_school_id(){
        Models\Program::UpdateSchoolId();
        $p = Models\Program::first();
        $this->assertTrue($p->school_id > 0);
    }

    public function test_update_school_program_count(){
        $school = Models\School::first();
        $school->program_master = 0;
        $school->save();

        Models\School::UpdateProgramCount();
        $school = Models\School::find($school->id);
        $this->assertTrue($school->program_master > 0);
    }
}
