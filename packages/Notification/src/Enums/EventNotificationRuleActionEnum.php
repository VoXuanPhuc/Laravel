<?php
namespace Encoda\Notification\Enums;

enum EventNotificationRuleActionEnum: string
{
    case CREATE = "create";
    case UPDATE = "update";
    case DELETE = "delete";
}
