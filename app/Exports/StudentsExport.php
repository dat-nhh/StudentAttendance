<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;

class StudentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $students;
    protected $attendances;
    protected $lessons;

    public function __construct($students, $attendances, $lessons)
    {
        $this->students = $students;
        $this->attendances = $attendances;
        $this->lessons = $lessons;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->students as $student) {
            $row = [];
            $row[] = $student->surname . ' ' . $student->forename;
            $row[] = $student->id;
            $row[] = $student->email;
            foreach ($this->lessons as $lesson) {
                $attendance = $this->attendances->where('student', $student->id)->where('lesson', $lesson->id)->first();
                $row[] = $attendance ? $attendance->status : '';
            }
            $data[] = $row;
        }
        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['Họ Tên', 'MSSV', 'Email'];
        foreach ($this->lessons as $lesson) {
            $headings[] = Carbon::parse($lesson->date)->format('d/m/Y');
        }
        return $headings;
    }

    public function styles($sheet)
    {
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
        ];

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleArray);

        $rowCount = count($this->students);
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 2; $row <= $rowCount + 1; $row++) {
            for ($col = 3; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                if ($cellValue === 'vắng') {
                    $sheet->getStyleByColumnAndRow($col, $row)->getFont()->getColor()->setARGB(Color::COLOR_RED);
                } elseif ($cellValue === 'trễ') {
                    $sheet->getStyleByColumnAndRow($col, $row)->getFont()->getColor()->setARGB(Color::COLOR_YELLOW);
                }
            }
        }
    }
}
