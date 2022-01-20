<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models;
use App\Imports\UnidbImport;
use App\Imports\CampusImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportTest extends TestCase
{
    /**
     * @requires PHP >= 2.0
     */
    public function test_import_schools()
    {
        $path = public_path('/uploads/import/');
        $files = array_diff(scandir($path), array('.', '..'));

        foreach($files as $p)
        {
            Excel::import(new UnidbImport, $path . $p);
        }
    }

    public function test_split_name()
    {
        $cn = "语言与管理学院";
        $en = "Kulliyyah of Languages and Management";
        $list = [
            $cn . ' ' . $en,
            $cn . ' /  ' . $en,
            $cn . '
            ' . $en,
            $cn . ' /
              ' . $en,
        ];

        foreach($list as $str){
            $arr = CampusImport::splitName($str);
            $this->assertEquals($arr[0], $cn);
            $this->assertEquals($arr[1], $en);
        }

        ///////////////////

        $name = "会计硕士/
Bachelor of Accounting (Hons) （MACC) ";
        $arr = CampusImport::splitName($name);
        $this->assertEquals($arr[0], "会计硕士");

        //////////////////
        $name = " ISDEV / ISDEV	";
        $arr = CampusImport::splitName($name);
        $this->assertEquals($arr[0], "ISDEV");

        //////////
        $name = "Department of Data Science";
        $arr = CampusImport::splitName($name);
        $this->assertEquals($arr[0], trim($name));
    }
}
