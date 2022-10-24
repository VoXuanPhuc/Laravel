<?php

namespace Encoda\BCP\Enums;

Enum BCPStatusEnum: string
{
    case IN_PROGRESS = "in_progress";
    case NEEDS_ATTENTION = "needs_attention";
    case OVER_DUE = "over_due";
    case UP_TO_DATE = "up_to_date";
}
