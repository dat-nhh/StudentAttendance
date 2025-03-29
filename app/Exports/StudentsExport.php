<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $headings = ['Họ Tên', 'MSSV'];
        foreach ($this->lessons as $lesson) {
            $headings[] = \Carbon\Carbon::parse($lesson->date)->format('d-m');
        }
        return $headings;
    }

    public function styles($sheet)
    {
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            // 'fill' => [
            //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //     'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW],
            // ],
        ];

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleArray);


        $rowCount = count($this->students); 
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn()); 

        for ($row = 2; $row <= $rowCount + 1; $row++) { 
            for ($col = 3; $col <= $highestColumnIndex; $col++) { 
                $cellValue = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                if ($cellValue === 'vắng') {
                    $sheet->getStyleByColumnAndRow($col, $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $sheet->getStyleByColumnAndRow($col, $row)->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                } elseif ($cellValue === 'trễ') {
                    $sheet->getStyleByColumnAndRow($col, $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $sheet->getStyleByColumnAndRow($col, $row)->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
                }
            }
        }
    }
}
