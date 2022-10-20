<?php

namespace Encoda\EasyLog\Entities;

use Illuminate\Contracts\Config\Repository;

class EasyLogStatus
{
    protected bool $enabled = true;

    public function __construct(Repository $config)
    {
        $this->enabled = $config['easylog.enabled'];
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return $this->enabled = true;
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        return $this->enabled = false;
    }

    /**
     * @return bool
     */
    public function disabled(): bool
    {
        return $this->enabled === false;
    }
}
