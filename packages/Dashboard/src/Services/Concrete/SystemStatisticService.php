<?php

namespace Encoda\Dashboard\Services\Concrete;

use Encoda\Dashboard\DTOs\SystemStatisticDTO;
use Encoda\Dashboard\Services\Interfaces\SystemStatisticServiceInterface;
use Encoda\MultiTenancy\Models\Tenant;
use Illuminate\Support\Facades\DB;

class SystemStatisticService implements SystemStatisticServiceInterface
{

    public function getSystemStatistics()
    {

        $query = "SELECT
                    ( SELECT COUNT(*) FROM organizations ) AS organizationNum ,
                    ( SELECT COUNT(*) FROM users ) AS userNum
                ";


        $result = DB::select( DB::raw( $query ) );

        return new SystemStatisticDTO((array)$result[0]);
    }
}
