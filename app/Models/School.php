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
}
