<?php

namespace Encoda\Dependency\Services\Interfaces;

use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Http\Requests\Dependency\CreateDependencyRequest;
use Encoda\Dependency\Http\Requests\Dependency\UpdateDependencyRequest;
use Encoda\Organization\Models\Organization;

interface DependencyServiceInterface
{
    public function listDependency(string $scenarioUID);

    public function getDependency(string $scenarioUID, string $uid);

    public function createDependency(CreateDependencyRequest $request, string $scenarioUID);

    public function updateDependency(UpdateDependencyRequest $request, string $scenarioUID, string $uid);

    public function deleteDependency(string $scenarioUID, string $uid);
}
