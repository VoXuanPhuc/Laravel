<?php

namespace Encoda\BCP\Exports;

use Encoda\BCP\Models\BCP;
use Encoda\BIA\Enums\BIAStatusEnum;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BCPRecordExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{

    protected BCP $bcp;
    public function __construct( $bcpObj )
    {
        $this->bcp = $bcpObj;
    }


    public function array(): array
    {
       return [
            $this->bcp
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
            'BCP Plan Name',
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
