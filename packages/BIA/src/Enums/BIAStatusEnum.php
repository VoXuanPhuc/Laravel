<?php

namespace Encoda\BIA\Enums;

Enum BIAStatusEnum: int
{
    case NOT_STARTED = 1;
    case IN_PROGRESS = 2;
    case NEEDS_ATTENTION = 3;
    case OVER_DUE = 4;
    case UP_TO_DATE = 5;
    case APPROVED = 6;
}
