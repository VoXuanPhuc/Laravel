<?php

namespace Encoda\Supplier\Exports;

use Encoda\Supplier\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Collection $suppliers;


    /**
     * @param $suppliers
     */
    public function __construct( $suppliers )
    {
        $this->suppliers = $suppliers;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->suppliers;
    }

    public function headings(): array
    {
        return [
            'UID',
            'Supplier Name',
            'Address',
            'description',
            'Email',
            'Fax',
            'Phone number',
            'Is active',
            'Categories',
        ];
    }

    /**
     * @param mixed $supplier
     * @return array
     */
    public function map( $supplier ): array
    {
        return [
            $supplier->uid,
            $supplier->name,
            $supplier->address,
            $supplier->description,
            $supplier->email,
            $supplier->fax,
            $supplier->phone_number,
            $supplier->is_active,
            $this->getCategoriesName( $supplier )
        ];
    }


    /**
     * @param Supplier $supplier
     */
    protected function getCategoriesName( $supplier )
    {
        return $supplier->categories->pluck('name');

    }

    /**
     * @param Worksheet $sheet
     * @return \bool[][][]
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
