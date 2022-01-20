<?php

namespace App\Imports;

use App\Models\Campus;
use App\Models\College;
use App\Models\Program;
use App\Models\School;
use App\Models\ModelHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class CampusImport implements ToCollection, WithEvents, SkipsUnknownSheets
{
    use RegistersEventListeners;

    static $campusNameCN = '';
    static $campusNameEN = '';
    static $format = [];

    private $currentSchool = null;
    private $currentCampus = null;
    private $currentCollege = null;
    private $columns = Null;

    public function onUnknownSheet($sheetName)
    {
        if (!is_numeric($sheetName)) {
            Log::warning("An unkown sheet {$sheetName} was skipped");
        }
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        $sheetTitle = $event->getSheet()->getDelegate()->getTitle();
        Log::debug(">> import sheet: " . $sheetTitle);

        $names = self::splitName($sheetTitle);
        self::$campusNameCN = $names[0];
        self::$format = include 'format.php';
    }

    public static function splitName($name)
    {
        if (strpos($name, "/") !== false) {
            $arr = explode("/", $name);
            return [trim($arr[0]), trim($arr[1])];
        }

        $from = ["\r", "\n", "\\", "（", ") "];
        $to = ["", "", " ", "(", ")"];
        $name = str_replace($from, $to, $name);

        $arr = preg_split("/([a-zA-Z0-9]+)/", trim($name), 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        for ($i = count($arr) - 1; $i >= 0; $i--) {
            // find the last chinese word in the sentence
            if (mb_strlen($arr[$i], 'utf-8') != strlen($arr[$i])) {
                break;
            }

        }
        $ret = [
            trim(implode("", array_slice($arr, 0, $i + 1))),
            trim(implode("", array_slice($arr, $i + 1))),
        ];

        // only English in name, no Chinese
        if ($i < 0) {
            $ret[0] = $ret[1];
        }
        return $ret;
    }

    //////////////////////////////////////////////////////////

    public function collection(Collection $collection)
    {
        //导入校区
        $this->currentSchool = School::find(SchoolImport::$currentSchoolId);
        $campus = Campus::findOrCreate($this->currentSchool->id, self::$campusNameCN);
        $campus->en_name = self::$campusNameEN;
        $campus->school_name = $this->currentSchool->name;
        $campus->updated_at = now();
        $campus->school()->associate($this->currentSchool);
        $campus->save();
        $this->currentCampus = $campus;
        Log::debug("import campus:" . $campus->name . '/' . $campus->id);

        // 得到中文标题对应的英文名
        $merge = function ($head1, $head2) {return $head2 ? trim($head2) : trim($head1);};
        $namesCN = array_map($merge, $collection[0]->toArray(), $collection[1]->toArray());
        $namesCN = array_diff($namesCN, array(''));

        $translate = function ($nameCN) {return $nameCN ? self::$format['program'][trim($nameCN)] : '';};
        $namesEN = array_map($translate, $namesCN);

        $this->columns = ModelHelper::ColumnNameAndComment((new Program)->getTable());

        //导入本校数据
        $collection = $collection->skip(2);
        $iterator = $collection->getIterator();
        while ($row = $iterator->current()) {
            if ($this->_isCollegeRow($row)) {
                $this->_importCollege($row);
            } else {
                $this->_importProgram($row, $namesEN);
            }
            $iterator->next();
        }
    }

    private function _isEmptyRow($row)
    {
        foreach($row as $item){
            if(!empty($item)) return false;
        }
        return true;
    }

    private function _isCollegeRow($row)
    {
        foreach ($row->skip(1) as $item) {
            if (!empty($item)) return false;
        }
        return true;
    }

    private function _importCollege($row)
    {
        if($this->_isEmptyRow($row)) return;

        $names = self::splitName($row[0]);
        if (empty($names[0])) {
            Log::warning("invalid college name: " . var_export($row, true));
            return;
        }

        $college = College::findOrCreate($this->currentCampus->id, $names[0]);
        $college->en_name = $names[1];
        $college->school_name = $this->currentCampus->school_name;
        $college->updated_at = now();
        $college->campus()->associate($this->currentCampus);
        $college->save();
        Log::debug("import college:" . $names[0] . '/' . $college->id);
        $this->currentCollege = $college;
    }

    private function _importProgram($row, $namesEN)
    {
        if($this->_isEmptyRow($row)) return;

        $names = self::splitName($row[0]);
        $data = [];
        $data['name'] = $names[0];
        $data['en_name'] = $names[1];
        $data['school_name'] = $this->currentSchool->name;
        $data['school_en_name'] = $this->currentSchool->en_name;

        for ($i = 1; $i < count($namesEN); $i++) { // skip the first column: name / en_name
            $key = $namesEN[$i];
            $value = trim($row[$i]);
            if(in_array($value, ["无", "-", "——"], TRUE)) $value = '';

            if (strpos($key, 'is_') === 0) {
                $value = 'Y' == strtoupper($row[$i]);
            }else if($key == 'mqf_level') {
                if (!array_key_exists($value, self::$format['mqf_level'])) {
                    $data[$key] = $value;
                    Log::warning("ignore a program due to unknown mqf_leve:" . var_export($data, true));
                    return;
                }
                $value = self::$format['mqf_level'][$value];
            }else if(empty($value) && array_key_exists($key, $this->columns)){
                if($this->columns[$key]->type == 'int') $value = 0;
                if($this->columns[$key]->type == 'decimal') $value = 0.0;
            }
            $data[$key] = $value;
        }

        $program = Program::findOrCreate($this->currentCollege->id, $data['name']);
        $program->updated_at = now();
        $program->college()->associate($this->currentCollege);
        $program->school()->associate($this->currentSchool);
        $program->fill($data);
        $program->save();
        Log::debug("import program:" . $names[0] . '/' . $program->id);
    }
}
