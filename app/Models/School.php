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

    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function programsFilteredByName($keyword){
        return $this->programs()
            ->where('en_name', 'like', '%' . $keyword . '%')
            ->orWhere('name', 'like', '%'. $keyword . '%')
            ->orderBy('level')
            ->get();
    }

    /**
     * find the schools with program name containing $search
     */
    static public function SearchByProgram($search)
    {
        $sql = "select school_id, level, count(id) as c from unidb_program
            where name like '%" . $search . "%' or en_name like '%" . $search . "%' group by school_id, level";
        $result = \DB::select($sql);

        $schools = [];
        foreach($result as $p){
            if (array_key_exists($p->school_id, $schools)){
                $s = $schools[$p->school_id];
            }else{
                $s = School::find($p->school_id);
            }

            if ($p->level == Program::TYPE_DEGREE){
                $s->program_degree = $p->c;
            }else if($p->level == Program::TYPE_MASTER){
                $s->program_master = $p->c;
            }else if($p->level == Program::TYPE_DOCTOR){
                $s->program_doctor = $p->c;
            }
            $schools[$p->school_id] = $s;
        }
        return array_values($schools);
    }

    /**
     *  update programs count for all schools
     */
    static public function UpdateProgramCount(){
        foreach(School::all() as $s){
            $s->program_degree = Program::ProgramCount($s->id, Program::TYPE_DEGREE);
            $s->program_master = Program::ProgramCount($s->id, Program::TYPE_MASTER);
            $s->program_doctor = Program::ProgramCount($s->id, Program::TYPE_DOCTOR);
            $s->save();
        }
    }
}
