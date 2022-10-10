<?php

namespace Encoda\Dependency\Services\Interfaces;

use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Models\Dependency;
use Encoda\Dependency\Models\DependencyDetail;

interface DependencyDetailServiceInterface
{
    public function createDependencyDetail(Dependency $dependency, array $data): ?DependencyDetail;

    public function getDependencyObject(DependencyObjectTypes $type, string $uid);

}
