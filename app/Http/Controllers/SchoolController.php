<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    public function index(Request $request, $id)
    {
        $school = School::find($id);
        if (empty($school)) {
            abort(403, 'No such school:' . $id);
        }

        $datas = ["school" => $school,
            'search' => ""];
        return view('school', $datas);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $id = $request->input('school_id');

        $school = School::find($id);
        $datas = ["school" => $school, 'search' => $search];
        return view('school', $datas);
    }

    public function fetch(Request $request)
    {
        if (!$request->ajax()) {
           return json_encode(['error' => 'ajax is needed']);
        }

        $level = $request->get("level");
        $school_id = $request->get("school_id");
        $search = $request->get("search");

        $school = School::find($school_id);
        $programs = $school->programsFilteredByLevel($level, $search);
        return json_encode($programs);
    }
}
