<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHelper
{
    public static function ColumnNameAndComment($model_name){
        $sql = "SELECT column_name as name, COLUMN_COMMENT as comment, DATA_type as type FROM information_schema.COLUMNS where TABLE_NAME = '%s' and COLUMN_COMMENT <> ''";
        $list = \DB::select(sprintf($sql, $model_name));
        return array_filter($list, function ($c) {return strpos($c->name, '_id') != strlen($c->name) - 3;});
    }
}
