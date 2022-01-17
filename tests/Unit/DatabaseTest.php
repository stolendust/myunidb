<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models;

class DatabaseTest extends TestCase
{
    public function test_table_structure(){
        $school = new Models\School;
        $cs = Models\ModelHelper::ColumnNameAndComment($school->getTable());
        $this->assertTrue(count($cs) > 0);

        // convert object's properties into dictionary
        $data = array_map( function($o){ return (array)$o; }, $cs);
        $this->assertTrue(count($data) > 0);
        $this->assertEquals($cs[0]->name, $data[0]['name']);

        $cs = Models\ModelHelper::ColumnNameAndComment('unidb_program');
        $this->assertTrue(count($cs) > 0);
        $this->assertEquals($cs[0]->name, $data[0]['name']);
    }

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

    public function test_search_school_by_program(){
        $schools = Models\School::SearchByProgram('business');

        $this->assertNotEmpty($schools);
        $this->assertTrue($schools[0]->program_master > 0);

        /* filtered program count is less than the total */
        $s = Models\School::find($schools[0]->id);
        $this->assertTrue($schools[0]->program_master < $s->program_master);

        $schools_music = Models\School::SearchByProgram('音乐');
        $this->assertTrue(count($schools) > count($schools_music));
    }

    public function test_filter_programs_of_school(){
        $schools = Models\School::SearchByProgram('business');

        $s = $schools[0];
        $this->assertTrue(count($s->programs) > 0);

        $programs = $s->programsFilteredByName('busine');
        $this->assertTrue(count($programs) > 0);
        $p = $programs[0];
        echo $p->mqf_level. " / " . $p->en_name . PHP_EOL;
    }

    public function test_fetch_programs_by_level(){
        $level = Models\Program::LEVEL_DEGREE;
        $school = Models\School::first();

        $retProgram = Models\Program::findBySchoolAndLevel($school->id,$level);
        $this->assertTrue(count($retProgram) > 0);
        echo $retProgram[0]->college->name;
        echo PHP_EOL;

        ////////////////////////////////
        $retSchool = $school->programsFilteredByLevel($level);
        $this->assertEquals(count($retProgram), count($retSchool));
        echo $retSchool[0]->college->name;
        echo PHP_EOL;

        $retSchoolSearch = $school->programsFilteredByLevel($level, "business");
        $this->assertTrue(count($retSchoolSearch) < count($retSchool));
        file_put_contents('tests/test_ret.html', json_encode($retSchoolSearch));
    }
}
