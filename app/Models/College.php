<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//学院
class College extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "unidb_college";

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function programs() {
        return $this->hasMany(Program::class);
    }

    public static function findOrCreate($campus_id, $name)
    {
        $obj = static::where('campus_id', $campus_id)->where('name',$name)->first();
        if(!$obj){
            $obj = new static;
            $obj->name = $name;
        }
        return $obj;
    }
}
