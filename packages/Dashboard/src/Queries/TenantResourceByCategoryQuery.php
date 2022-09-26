<?php

namespace Encoda\Dashboard\Queries;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TenantResourceByCategoryQuery extends BaseQuery
{


    /**
     * @return $this
     */
    public function buildQuery(): TenantResourceByCategoryQuery
    {

      $this->query = DB::table('resources')
                ->where('resources.organization_id', '=', $this->tenant->id )
                ->selectRaw('count(*) as num, resource_categories.name as category' )
                ->join('resource_categories', 'resources.resources_category_id', '=', 'resource_categories.id' )
                ->whereBetween('resources.created_at', [$this->from->toDateTimeString(), $this->to->toDateTimeString()])
                ->groupBy('resource_categories.name')
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
                'label' => $item->category,
                'value' => (int) $item->num,
            ];
        }, $result );
    }
}
