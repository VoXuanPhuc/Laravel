<?php

namespace Encoda\BIA\Enums;

use Encoda\Core\Enums\EnumInterface;

Enum BIAStatusEnum: int implements EnumInterface
{
    case NOT_STARTED = 1;
    case IN_PROGRESS = 2;
    case NEEDS_ATTENTION = 3;
    case OVER_DUE = 4;
    case UP_TO_DATE = 5;
    case APPROVED = 6;

    /**
     * @return string
     */
    public function friendlyName(): string
    {
        return match ( $this ) {
            self::NOT_STARTED => 'Not Started',
            self::IN_PROGRESS => 'In Progress',
            self::NEEDS_ATTENTION => 'Needs Attention',
            self::OVER_DUE => 'Over Due',
            self::UP_TO_DATE => 'Up to date',
            self::APPROVED => 'Approved',

        };
    }
}
