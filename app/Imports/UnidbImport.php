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
}
