<?php

namespace Encoda\Dependency\Exports;

use Encoda\Dependency\Enums\DependencyScenarioStatusEnum;
use Encoda\Dependency\Models\DependencyScenario;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DependencyScenarioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{


    protected Collection $dependencyScenarios;

    /**
     * @param Collection $dependencyScenarios
     */
    public function __construct( $dependencyScenarios )
    {
        $this->dependencyScenarios = $dependencyScenarios;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->dependencyScenarios;
    }

    public function headings(): array
    {
        return [
            'UID',
            'Dependency Scenario Name',
            'Description',
            'Status',
            'Target Dependencies',
            'Upstream Dependencies',
            'Downstream Dependencies',

        ];
    }

    /**
     * @param DependencyScenario $dependencyScenario
     * @return array
     */
    public function map( $dependencyScenario ): array
    {
        return [

            $dependencyScenario->uid,
            $dependencyScenario->name,
            $dependencyScenario->description,
            DependencyScenarioStatusEnum::from(  $dependencyScenario->status )->name,
            $this->getTargetDependencyNames( $dependencyScenario ),
            $this->getUpstreamDependencyNames( $dependencyScenario ),
            $this->getDownstreamDependencyNames( $dependencyScenario ),

        ];
    }

    /**
     * @param DependencyScenario $dependencyScenario
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    private function getTargetDependencyNames(DependencyScenario $dependencyScenario ) {
        return $dependencyScenario->targetDependencies()->map( function( $item ) {
            return $item->name;
        });
    }

    /**
     * @param DependencyScenario $dependencyScenario
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    private function getUpstreamDependencyNames(DependencyScenario $dependencyScenario ) {
        return $dependencyScenario->upstreamDependencies()->map( function( $item ) {
            return $item->name;
        });
    }

    /**
     * @param DependencyScenario $dependencyScenario
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    private function getDownstreamDependencyNames(DependencyScenario $dependencyScenario ) {
        return $dependencyScenario->downstreamDependencies()->map( function( $item ) {
            return $item->name;
        });
    }

    /**
     * @param Worksheet $sheet
     * @return bool[][][]
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
