<?php

namespace Encoda\Dashboard\Queries;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TenantActivityByStatusQuery extends BaseQuery
{


    /**
     * @return $this
     */
    public function buildQuery(): TenantActivityByStatusQuery
    {

      $this->query = DB::table('activities')
                ->where('organization_id', '=', $this->tenant->id )
                ->selectRaw('count(*) as num, status' )
                ->whereBetween('created_at', [$this->from->toDateTimeString(), $this->to->toDateTimeString()])
                ->groupBy('status')
                ->orderBy('status')
        ;

      return $this;
    }

    public function transform()
    {
        if( !$this->query ) {
            return [];
        }

        $result = $this->query->get()->toArray();

        return array_map( function( $item ) {
            return [
                'label' => $this->mapStatus( $item->status ),
                'value' => (int) $item->num,
            ];
        }, $result );
    }

    /**
     * @param $status
     * @return string
     */
    protected function mapStatus( $status ) {

        return match ($status) {
            1 => 'Created',
            2 => 'In Progress',
            3 => 'Finished',
            4 => 'Canceled',
            default => 'N/A',
        };
    }
}
