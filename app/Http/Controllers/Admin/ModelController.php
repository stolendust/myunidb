<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelHelper;
use App\Models\School;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * fetch data through ajax, supporting server side pagination
     */
    function list(Request $request) {
        if (!$request->ajax()) {
            return json_encode(['error' => 'ajax is needed']);
        }

        $model = $request->input("model");
        $tableName = 'unidb_'.$model;
        $table = \DB::table($tableName);

        $columnNameAndComment = ModelHelper::ColumnNameAndComment($tableName);
        $columns = array_map(function ($c) {return $c->name;}, $columnNameAndComment);

        $totalData = $table->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // fetch data
        if (empty($request->input('search.value'))) {
            $list = $table->offset($start)->limit($limit)->orderBy($order, $dir)->get();
        } else {
            $search = $request->input('search.value');
            $list = $table->where('en_name', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $table->where('en_name', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = [];
        foreach ($list as $item) {
            $data[] = (array) $item;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    ///////////////////////////////////////////////////////////////

    public function index(Request $request, $model)
    {
        $tableName = 'unidb_' . $model;
        $columns = ModelHelper::ColumnNameAndComment($tableName);
        return view('admin.model.index')->with('columns', $columns)->with('model', $model);
    }

    public function store(Request $request)
    {
        $schools = new School();
        $schools->name = $request->get('name');
        $schools->en_name = $request->get('en_name');
        $schools->save();
        return redirect('/schools');
    }

    public function show($model, $id)
    {
        //
    }

    public function edit($mode, $id)
    {
        $school = School::find($id);
        return view('school.edit')->with('school', $school);
    }

    public function update(Request $request, $model, $id)
    {
        $school = School::find($id);
        $schools->name = $request->get('name');
        $schools->en_name = $request->get('en_name');
        $school->save();

        return redirect('/schools');
    }
}
