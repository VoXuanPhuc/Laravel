<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Device;
use Encoda\Activity\Repositories\Interfaces\DeviceRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class DeviceRepository extends Repository implements DeviceRepositoryInterface
{
    
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Device::class;
    }
}
