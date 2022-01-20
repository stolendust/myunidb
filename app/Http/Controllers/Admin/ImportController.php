<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.import');
    }

    public function post(Request $request)
    {
        /*
        if($request->file('file')->isValid()){
            $path = $request->file->store('excels');
            Excel::import(new SchoolsImport, $path);
            return response()->json(['code'=>0]);
        }
        */

        $request->validate([
            'file' => 'required|mimes:xlx,xlsx|max:2048',
        ]);

        $fileName = 'data' . time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);

        return back()
            ->with('success','You have successfully upload file.')
            ->with('file',$fileName);
    }
}
