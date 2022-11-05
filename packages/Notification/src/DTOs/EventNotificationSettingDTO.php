<?php

namespace Encoda\Notification\DTOs;

class EventNotificationSettingDTO extends BaseDTO
{
    public array $method;
    public array $ruleAction;
    public array $ruleModel;
    public array $type;
    public array $replacements;
}
