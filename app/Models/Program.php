<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    const TYPE_DEGREE = 1;
    const TYPE_MASTER = 2;
    const TYPE_DOCTOR = 3;

    protected $guarded = [];
    protected $table = "unidb_program";

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    static public function ProgramCount($school_id, $type){
        return Program::where('school_id', $school_id)->where('typeid', $type)->count();
    }

    static public function UpdateSchoolId(){
        foreach(Program::all() as $p){
            $p->school_id = $p->college->school_id;
            $p->save();
        }
    }
}
