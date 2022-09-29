<?php

namespace Encoda\Resource\Exports;

use Encoda\Resource\Models\Resource;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResourceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{

    protected Collection $resources;


    /**
     * @param $resources
     */
    public function __construct( $resources )
    {
        $this->resources = $resources;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->resources;
    }

    public function headings(): array
    {
        return [
            'UID',
            'Resource Name',
            'Description',
            'Status',
            'Category',
            'Owners',

        ];
    }

    /**
     * @param mixed $resource
     * @return array
     */
    public function map( $resource ): array
    {
        return [
            $resource->uid,
            $resource->name,
            $resource->description,
            $resource->status,
            $resource->category->name,
            $this->getOwnerNames( $resource )
        ];
    }


    /**
     * @param Resource $resource
     */
    protected function getOwnerNames( $resource )
    {
        return $resource->owners->map( function( $owner ) {
            return $owner->full_name;
        });

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
