<?php
namespace Encoda\Notification\Enums;

enum EventNotificationRuleModelEnum: string
{
    case SUPPLIER = "supplier";
    case DIVISION = "division";
    case BUSINESS_UNIT = "businessUnit";
    case ACTIVITY = "activity";
    case EQUIPMENT = "equipment";
    case USER = "user";
}
