<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $category;

    public function __construct($startDate, $endDate, $category = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->category = $category;
    }

    public function collection()
    {
        return Attendance::with('employee')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->when($this->category, function($query) {
                return $query->whereHas('employee', function($q) {
                    $q->where('category_id', $this->category);
                });
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Induk / NIS / NIP',
            'Nama Lengkap',
            'Kategori',
            'Kelas / Jabatan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status Kehadiran',
            'Shift Terdeteksi',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->employee->code,
            $attendance->employee->name,
            $attendance->employee->category ? strtoupper($attendance->employee->category->name) : '-',
            $attendance->employee->position ? $attendance->employee->position->name : '-',
            $attendance->date,
            $attendance->check_in ?? '-',
            $attendance->check_out ?? '-',
            strtoupper($attendance->status),
            $attendance->detected_shift ? strtoupper($attendance->detected_shift) : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0F766E'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:I{$highestRow}");

                $sheet->getStyle("A1:I{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD1D5DB'],
                        ],
                    ],
                ]);

                $sheet->getStyle("C2:I{$highestRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Zebra stripes untuk mempermudah baca data panjang.
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:I{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF8FAFC');
                    }
                }
            },
        ];
    }
}
