<?php

namespace App\Imports;

use App\Models\School;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Validators\Failure;

class SchoolImport implements ToCollection, WithEvents
{
    use RegistersEventListeners;

    public static $config;
    public static $currentSchoolId;

    public static function beforeSheet(BeforeSheet $event)
    {
        $format = include 'format.php';
        self::$config = $format['school'];
    }

    public function collection(Collection $collection)
    {
        $name = $collection[0][1];
        Log::debug('>> import school:' . $name);

        $data = [];
        for ($i = 1; $i < count(self::$config); $i++) {
            $key = self::$config[$collection[$i][0]];
            $value = $collection[$i][1];

            if (strpos($key, '_time') !== false && is_numeric($value)) {
                $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }else if($key == "is_public"){
                $value = strpos($value, "公立") !==  FALSE;
            }

            $data[$key] = $value;
        }

        $school = School::findOrCreate($name);
        $school->updated_at = now();
        $school->fill($data);
        $school->save($data);

        self::$currentSchoolId = $school->id;
    }
}
