<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $student = new Student();
            $student->surname = $row[0];
            $student->forename = $row[1];
            $student->id = $row[2];
            $student->email = isset($row[3]) ? trim($row[3]) : '';
            $student->class = $this->class;

            $student->save();

            $lessons = Lesson::where('class', $student->class)->get();
            foreach ($lessons as $lesson) {
                $attendance = new Attendance();
                $attendance->lesson = $lesson->id;
                $attendance->student = $student->id;
                $attendance->status = 'Vắng';
                $attendance->save();
            }
        }
    }
}
