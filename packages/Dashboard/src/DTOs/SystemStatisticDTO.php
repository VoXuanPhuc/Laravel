<?php

namespace Encoda\Dashboard\DTOs;


class SystemStatisticDTO extends BaseDTO
{

    public int $organizationNum;
    public int $userNum;
    public int $taskNum;
    public int $notificationNum;

    public function __construct( $data = [] )
    {
        $this->organizationNum = $data['organizationNum'] ?? 0;
        $this->userNum = $data['userNum'] ?? 0;
        $this->taskNum = $data['taskNum'] ?? 0;
        $this->notificationNum = $data['notificationNum'] ?? 0;
    }
}
