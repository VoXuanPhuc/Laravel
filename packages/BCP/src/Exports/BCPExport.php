<?php

namespace Encoda\BCP\Exports;

use Encoda\BCP\Models\BCP;
use Encoda\BIA\Enums\BIAStatusEnum;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BCPExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{

    protected Collection $bcps;

    /**
     * @param Collection $bcps
     */
    public function __construct( $bcps )
    {
        $this->bcps = $bcps;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->bcps;
    }

    public function headings(): array
    {
        return [
            'UID',
            'BCP Name',
            'Status',
            'Due date',
            'Reports'
        ];
    }

    /**
     * @param BCP $bcp
     * @return array
     */
    public function map( $bcp ): array
    {
        return [
            $bcp->uid,
            $bcp->name,
            BIAStatusEnum::from( $bcp->status )->name,
            $bcp->due_date,

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
