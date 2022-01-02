<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\School;
use App\Models\Program;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_fetch_programs_ajax()
    {
        $school = School::all()->random();
        $response = $this->post('/school/fetch',
            ['level'=>Program::LEVEL_DEGREE,'school_id'=>$school->id]);
        #$response->assertStatus(200);
    }
}
