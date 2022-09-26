<?php

namespace Encoda\MultiTenancy\Exceptions;

use Exception;
use Encoda\MultiTenancy\Tasks\SwitchTenantTask;

class TaskCannotBeExecuted extends Exception
{
    public static function make(SwitchTenantTask $task, string $reason): self
    {
        $taskClass = $task::class;

        return new static("Task `{$taskClass}` could not be executed. Reason: {$reason}");
    }
}
