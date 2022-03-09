<?php

namespace App\Imports;
use App\Models\School;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class UnidbImport implements WithMultipleSheets, WithEvents
{
    use RegistersEventListeners;

    public static function beforeImport(BeforeImport $event) {
    }

    public static function afterImport(AfterImport $event)
    {
        $school = School::find(SchoolImport::$currentSchoolId);
        $school->updateProgramCount();
        Log::debug("<< import school done and program counts updated:" . $school->name);
    }

    public function sheets(): array
    {
        $sheets = [ new SchoolImport() ];
        $index = 0;
        while ($index < 10 ) {
            $sheets[] = new CampusImport();
            $index++;
        }
        return $sheets;
    }

    public static function splitName($name)
    {
        // remove utf-8 space
        $name = trim(preg_replace('/\xc2\xa0/',' ',$name));
        $name = trim(preg_replace('/\xe2\x80\x93/','-',$name));

        // remove chars
        $from = ["\r", "\n", "\t","\\", "（", "）"];
        $to =   [" ",   " ",  " ", " ",  "(",  ")"];
        $name = trim(str_replace($from, $to, $name));

        // well formatted name: Chinese / English
        if (strpos($name, "|") !== false) {
            $arr = explode("|", $name);
            if (mb_strlen($arr[1], 'utf-8') != strlen($arr[1])){ // English / Chinese
                return [trim($arr[1]), trim($arr[0])];
            }else{
                return [trim($arr[0]), trim($arr[1])];
            }
        }

        // well formatted name: Chinese / English
        if (strpos($name, "/") !== false) {
            $arr = explode("/", $name);
            if (mb_strlen($arr[1], 'utf-8') != strlen($arr[1])){ // English / Chinese
                return [trim($arr[1]), trim($arr[0])];
            }else{
                return [trim($arr[0]), trim($arr[1])];
            }
        }

        // split name to parts and find the latest Chinese part from the end
        $arr = preg_split("/([a-zA-Z0-9]+)/", $name, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        for ($i = count($arr) - 1; $i >= 0; $i--) {
            if (mb_strlen($arr[$i], 'utf-8') != strlen($arr[$i])) {
                $a = trim($arr[$i]);
                break;
            }
        }

        $ret = [
            trim(implode("", array_slice($arr, 0, $i + 1))),
            trim(implode("", array_slice($arr, $i + 1))),
        ];
        // only English in name, no Chinese
        if ($i < 0)  $ret[0] = $ret[1];
        return $ret;
    }
}
