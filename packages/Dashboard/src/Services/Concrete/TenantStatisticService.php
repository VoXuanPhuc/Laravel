<?php

namespace Encoda\Dashboard\Services\Concrete;

use Carbon\Carbon;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Dashboard\DTOs\TenantStatisticDTO;
use Encoda\Dashboard\Queries\TenantActivityByStatusQuery;
use Encoda\Dashboard\Queries\TenantResourceByCategoryQuery;
use Encoda\Dashboard\Queries\TenantResourceByMonthQuery;
use Encoda\Dashboard\Services\Interfaces\TenantStatisticServiceInterface;
use Encoda\Identity\Models\Database\User;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;
use Encoda\Task\Models\Task;

class TenantStatisticService implements TenantStatisticServiceInterface
{

    public function __construct(

        protected ActivityRepositoryInterface $activityRepository,
        protected ResourceRepositoryInterface $resourceRepository
    )
    {
    }

    /**
     * Get Statistic
     */
    public function getSystemStatistics()
    {
        $tenantStatisticDTO = new TenantStatisticDTO();

        $tenantStatisticDTO->taskNum = Task::count();
        $tenantStatisticDTO->notificationNum = 1;
        $tenantStatisticDTO->userNum = User::count();
        $tenantStatisticDTO->activityNum = Activity::count();


        $this->getSixMonthsAgoResourceStatistic( $tenantStatisticDTO );
        $this->getActivityByStatusStatistic( $tenantStatisticDTO );
        $this->getResourcesByCategoryStatistic( $tenantStatisticDTO );


        return $tenantStatisticDTO;
    }

    /**
     * @param TenantStatisticDTO $tenantStatisticDTO
     */
    protected function getSixMonthsAgoResourceStatistic(TenantStatisticDTO $tenantStatisticDTO ) {

        $sixMonthAgo = Carbon::createFromTimestamp( strtotime("-6 months" ) );

        $tenantStatisticDTO->resourcesInMonths = (new TenantResourceByMonthQuery( $sixMonthAgo ) )
            ->buildQuery()
            ->transform();

    }

    /**
     * @param TenantStatisticDTO $tenantStatisticDTO
     */
    protected function getActivityByStatusStatistic( TenantStatisticDTO $tenantStatisticDTO ) {

        $sixMonthAgo = Carbon::createFromTimestamp( strtotime("-6 months" ) );

        $tenantStatisticDTO->activitiesByStatus = (new TenantActivityByStatusQuery( $sixMonthAgo ) )
            ->buildQuery()
            ->transform();

    }

    /**
     * @param TenantStatisticDTO $tenantStatisticDTO
     */
    protected function getResourcesByCategoryStatistic( TenantStatisticDTO $tenantStatisticDTO ) {

        $sixMonthAgo = Carbon::createFromTimestamp( strtotime("-6 months" ) );

        $tenantStatisticDTO->resourcesByCategory = (new TenantResourceByCategoryQuery( $sixMonthAgo ) )
            ->buildQuery()
            ->transform();

    }
}
