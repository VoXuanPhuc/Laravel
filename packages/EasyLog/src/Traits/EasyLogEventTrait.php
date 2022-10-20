<?php

namespace Encoda\EasyLog\Traits;

use Encoda\EasyLog\EasyLogger;

/**
 * @property $easyLog
 */
trait EasyLogEventTrait
{


    /**
     * @param string $event
     * @return $this
     */
    public function event(string $event): static
    {
        return $this->setEvent($event);
    }

    /**
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event): static
    {
        $this->easyLog->event = $event;

        return $this;
    }
}
