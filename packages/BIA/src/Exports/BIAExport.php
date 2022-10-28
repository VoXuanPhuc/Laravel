<?php

namespace Encoda\BIA\Exports;

use Encoda\BIA\Enums\BIAStatusEnum;
use Encoda\BIA\Models\BIA;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BIAExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{

    protected Collection $bias;

    /**
     * @param Collection $bias
     */
    public function __construct( $bias )
    {
        $this->bias = $bias;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->bias;
    }

    public function headings(): array
    {
        return [
            'UID',
            'BIA Name',
            'Status',
            'Due date',
            'Reports'
        ];
    }

    /**
     * @param BIA $bia
     * @return array
     */
    public function map( $bia ): array
    {
        return [
            $bia->uid,
            $bia->name,
            BIAStatusEnum::from( $bia->status )->name,
            $bia->due_date,


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
