<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    const LEVEL_DIPLOMA = 4;
    const LEVEL_DEGREE = 6;
    const LEVEL_MASTER = 7;
    const LEVEL_DOCTOR = 8;

    protected $guarded = [];
    protected $table = "unidb_program";

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    /**********************************************************
     * static functions
     ***********************************************************/

    public function findBySchoolAndLevel($school_id, $level)
    {
        return Program::with('college:name,id')
            ->select('name', 'en_name', 'school_years','tuition_total', 'college_id')
            ->where('school_id', $school_id)
            ->where('mqf_level', $level)
            ->get();
    }

    public static function ProgramCount($school_id, $type)
    {
        return Program::where('school_id', $school_id)->where('mqf_level', $type)->count();
    }

    public static function UpdateSchoolId()
    {
        foreach (Program::all() as $p) {
            $p->school_id = $p->college->school_id;
            $p->save();
        }
    }

    public static function findOrCreate($college_id, $name)
    {
        $obj = static::where('college_id', $college_id)->where('name',$name)->first();
        if(!$obj){
            $obj = new static;
            $obj->name = $name;
        }
        return $obj;
    }
}
