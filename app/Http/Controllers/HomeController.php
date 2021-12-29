<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\School;

class HomeController extends Controller
{
    public function index(){
        $schools = School::orderByRaw('-global_sort DESC')->get();
        return view('home', ["schools" => $schools, "search" => ""]);
    }

    public function search(Request $request){
        $search = $request->input('search');
        $schools = School::SearchByProgram($search);
        return view('home', ["schools" => $schools, 'search' => $search]);
    }
}
