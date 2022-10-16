<?php

namespace Encoda\Dependency\Services\Interfaces;

use Encoda\Dependency\Http\Requests\Scenario\CreateDependencyScenarioRequest;
use Encoda\Dependency\Http\Requests\Scenario\UpdateDependencyScenarioRequest;

interface DependencyScenarioServiceInterface
{
    public function listDependencyScenario();

    public function getDependencyScenario( string $uid);

    public function createDependencyScenario(CreateDependencyScenarioRequest $request);

    public function updateDependencyScenario(UpdateDependencyScenarioRequest $request, string $uid);

    public function deleteDependencyScenario(string $uid);

    public function export( $range = 'all' );
}
