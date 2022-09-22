<?php

namespace Encoda\Activity\Exports;

use Encoda\Activity\Models\Activity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{

    protected Collection $activities;

    /**
     * @param Collection $activities
     */
    public function __construct( $activities )
    {
        $this->activities = $activities;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'UID',
            'Activity Name',
            'Description',
            'Division',
            'Business Unit',
            'Roles',
            'Alternative Roles',
            'Min Required Resources',
            'Remote',
            'Utilities',
            'Remote Access Factors',
            'Onsite Location',
            'Required Applications/Software',
            'Data',
            'Data Location',
            'Equipments',

        ];
    }

    /**
     * @param Activity $activity
     * @return array
     */
    public function map( $activity ): array
    {
        return [
            $activity->uid,
            $activity->name,
            $activity->description,
            $activity->division ? $activity->division->name : '',
            $activity->businessUnit ? $activity->businessUnit->name : '',
            $this->getRoleNames( $activity ),
            $this->getAlternativeRoleNames( $activity ),
            $activity->min_people,
            // Remote screen
            $activity->is_remote,
            $this->getUtilityNames( $activity ),
            $this->getRemoteAccessFactorNames( $activity ),
            $activity->on_site_requires,

            // App screen
            $this->getRequiredAppNames( $activity ),
            $activity->itSolution ? $activity->itSolution->data : '',
            $activity->itSolution ? $activity->itSolution->location : '',
            $this->getEquipmentNames( $activity )

        ];
    }

    /**
     * @param Activity $activity
     */
    protected function getRoleNames( $activity )
    {
        return $activity->roles->map( function( $role ) {
            return $role->label;
        });

    }

    /**
     * @param Activity $activity
     */
    protected function getAlternativeRoleNames( $activity )
    {
        return $activity->alternativeRoles->map( function( $role ) {
            return $role->label;
        });

    }

    /**
     * @param Activity $activity
     */
    protected function getUtilityNames( $activity )
    {
        return $activity->utilities->map( function( $utility ) {
            return $utility->name;
        });

    }

    /**
     * @param Activity $activity
     */
    protected function getRemoteAccessFactorNames( $activity )
    {
        return $activity->remoteAccessFactors->map( function( $raf ) {
            return $raf->name;
        });

    }

    /**
     * @param Activity $activity
     */
    protected function getRequiredAppNames( $activity )
    {
        return $activity->applications->map( function( $app ) {
            return $app->name;
        });

    }
    /**
     * @param Activity $activity
     */
    protected function getEquipmentNames( $activity )
    {
        return $activity->equipments->map( function( $equipment ) {
            return $equipment->name;
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
