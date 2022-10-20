<?php

namespace Encoda\EasyLog\Entities;

use Illuminate\Database\Eloquent\Model;

class EventLogBag
{
    public function __construct(
        public string $event,
        public Model $model,
        public array $changes,
        public ?LogOptions $options = null
    ) {
        $this->options ??= $model->getEasyLogOptions();
    }
}
