<?php

namespace Encoda\Dependency\Services\Interfaces;

use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Models\Dependency;
use Encoda\Dependency\Models\DependencyDetail;

interface DependencyFactorServiceInterface
{


    public function getDependencyFactors();

}
