<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\School;

class SchoolController extends Controller
{
    public function index(Request $request, $id){
        $school = School::find($id);
        if(empty($school)){
            abort(403, 'No such school');
        }

        $datas = [ "school" => $school,
            'search' => "" ];
        return view('school', $datas);
    }

    public function search(Request $request){
        $search = $request->input('search');
        $id = $request->input('school_id');

        $school = School::find($id);
        $programs = $school->programsFilteredByName($search);

        $datas = [ "school" => $school,
            'programs' => $programs,
            'search' => $search ];
        return view('school', $datas);
    }
}
