<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//校区
class Campus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "unidb_campus";

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function colleges()
    {
        return $this->hasMany(College::class);
    }

    public static function findOrCreate($school_id, $name)
    {
        $obj = static::where('school_id', $school_id)->where('name',$name)->first();
        if(!$obj){
            $obj = new static;
            $obj->name = $name;
        }
        return $obj;
    }
}
