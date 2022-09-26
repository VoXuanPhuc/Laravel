<?php

namespace Encoda\Dashboard\DTOs;


class TenantStatisticDTO extends BaseDTO
{

    public array $resourcesInMonths;
    public array $resourcesByCategory;
    public array $activitiesByStatus;

    public int $userNum;
    public int $taskNum;
    public int $notificationNum;
    public int $activityNum;

}
