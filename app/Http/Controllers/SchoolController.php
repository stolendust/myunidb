<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    public function school(Request $request, $idOrShortName)
    {
        $search = $request->query('search', '');
        if(is_numeric($idOrShortName)){
            $school = School::find($idOrShortName);
        }else{
            $school = School::where('short_name', $idOrShortName)->firstOrFail();
        }

        $datas = ["school" => $school, 'search' => $search];
        return view('school', $datas);
    }

    public function fetch(Request $request)
    {
        if (!$request->ajax()) {
           return json_encode(['error' => 'ajax is needed']);
        }

        $level = $request->get("mqf_level");
        $school_id = $request->get("school_id");
        $search = $request->get("search");

        $school = School::find($school_id);
        $programs = $school->programsFilteredByLevel($level, $search);
        return json_encode($programs);
    }
}
