<?php

namespace Encoda\BIA\Exports;

use Encoda\BIA\Enums\BIAStatusEnum;
use Encoda\BIA\Models\BIA;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BIARecordExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{

    protected BIA $bia;
    public function __construct( $biaObj )
    {
        $this->bia = $biaObj;
    }


    public function array(): array
    {
       return [
            $this->bia
       ];
    }

    public function map($row): array
    {
        return [
            $row->uid,
            $row->name,
            BIAStatusEnum::from( $row->status )->name,
            $row->due_date,
        ];
    }

    public function headings(): array
    {
        return [
            'UID',
            'BIA Name',
            'Status',
            'Due date',
        ];
    }


    /**
     * @param Worksheet $sheet
     * @return bool[][][]
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
