<?php

namespace Encoda\BIA\Enums;

Enum BIAStatusEnum: string
{
    case NOT_STARTED = "not_started";
    case IN_PROGRESS = "in_progress";
    case NEEDS_ATTENTION = "needs_attention";
    case OVER_DUE = "over_due";
    case UP_TO_DATE = "up_to_date";
}
