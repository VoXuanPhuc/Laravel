<?php

namespace Encoda\Dashboard\Queries;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TenantResourceByMonthQuery extends BaseQuery
{


    /**
     * @return $this
     */
    public function buildQuery(): TenantResourceByMonthQuery
    {

      $this->query = DB::table('resources')
                ->where('organization_id', '=', $this->tenant->id )
                ->selectRaw('count(*) as num, MONTHNAME(created_at) as month' )
                ->whereBetween('created_at', [$this->from->toDateTimeString(), $this->to->toDateTimeString()])
                ->groupBy('month')
                ->orderBy('created_at')
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
                'label' => $item->month,
                'value' => (int) $item->num,
            ];
        }, $result );
    }
}
