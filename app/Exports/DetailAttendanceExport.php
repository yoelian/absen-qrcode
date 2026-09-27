<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailAttendanceExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $attendances;
    protected $startDate;
    protected $endDate;
    protected $posName;

    public function __construct($attendances, $startDate, $endDate, $posName)
    {
        $this->attendances = $attendances;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->posName = $posName;
    }

    public function view(): View
    {
        return view('reports.excel_detail', [
            'attendances' => $this->attendances,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'posName' => $this->posName,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['bold' => true]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}
