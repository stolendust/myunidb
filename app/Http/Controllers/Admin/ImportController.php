<?php

namespace App\Http\Controllers\Admin;

use App\Models\School;
use App\Imports\UnidbImport;
use App\Imports\SchoolImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get()
    {
        return view('admin.import');
    }

    public function post(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlx,xlsx|max:2048',
        ]);

        $fileNameOld = $request->file->getClientOriginalName();
        $fileName = pathinfo($fileNameOld, PATHINFO_FILENAME);
        $fileName .= '-' . time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);

        try {
            Excel::import(new UnidbImport, public_path('uploads') . "/" . $fileName);
            //$school = School::find(SchoolImport::$currentSchoolId);
        } catch (Exception $e) {
            Log::error($e);
            $err = ["数据导入出现异常", $e->getMessage(), $e->getTraceAsString()];
            return back()->with('errors', $err);
        }

        return back()
            ->with('success', '已成功导入: ' . $fileNameOld)
            ->with('file', $fileName);
    }
}
