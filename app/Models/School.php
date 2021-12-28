<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//校区
class School extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "unidb_school";

    public function campuses(){
        return $this->hasMany(Campus::class);
    }

    public function colleges() {
        return $this->hasMany(College::class);
    }

    static public function UpdateProgramCount(){
        foreach(School::all() as $s){
            $s->program_degree = Program::ProgramCount($s->id, Program::TYPE_DEGREE);
            $s->program_master = Program::ProgramCount($s->id, Program::TYPE_MASTER);
            $s->program_doctor = Program::ProgramCount($s->id, Program::TYPE_DOCTOR);
            $s->save();
        }
    }
}
