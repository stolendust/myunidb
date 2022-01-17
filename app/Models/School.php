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

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function programsFilteredByName($keyword){
        return $this->programs()
            ->where('en_name', 'like', '%' . $keyword . '%')
            ->orWhere('name', 'like', '%'. $keyword . '%')
            ->orderBy('mqf_level')
            ->get();
    }

    public function programsFilteredByLevel($level,$search=""){
        $query = $this->programs()
            ->with('college:name,en_name,id')
            ->select('name', 'en_name',  'mode','school_years','tuition_total', 'college_id', 'ielts', 'intakes')
            ->where('mqf_level', $level);

        if(!empty($search)){
            $query->where(function($q) use($search){
               $q->where('en_name', 'like', '%' . $search. '%')
                ->orWhere('name', 'like', '%'. $search. '%');
           });
        }
        return $query->get();
    }

    /**
     * find the schools with program name containing $search
     */
    static public function SearchByProgram($search)
    {
        $sql = "select school_id, mqf_level, count(id) as c from unidb_program
            where name like '%" . $search . "%' or en_name like '%" . $search . "%' group by school_id, mqf_level";
        $result = \DB::select($sql);

        $schools = [];
        foreach($result as $p){
            if (array_key_exists($p->school_id, $schools)){
                $s = $schools[$p->school_id];
            }else{
                $s = School::find($p->school_id);
                $s->program_degree = 0;
                $s->program_master = 0;
                $s->program_doctor = 0;
            }

            if ($p->mqf_level == Program::LEVEL_DEGREE){
                $s->program_degree = $p->c;
            }else if($p->mqf_level == Program::LEVEL_MASTER){
                $s->program_master = $p->c;
            }else if($p->mqf_level == Program::LEVEL_DOCTOR){
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
            $s->program_degree = Program::ProgramCount($s->id, Program::LEVEL_DEGREE);
            $s->program_master = Program::ProgramCount($s->id, Program::LEVEL_MASTER);
            $s->program_doctor = Program::ProgramCount($s->id, Program::LEVEL_DOCTOR);
            $s->save();
        }
    }
}
