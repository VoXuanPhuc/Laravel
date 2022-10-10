<?php

namespace Encoda\Dependency\Http\Controllers;

use Encoda\Dependency\Http\Requests\Scenario\CreateDependencyScenarioRequest;
use Encoda\Dependency\Http\Requests\Scenario\UpdateDependencyScenarioRequest;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;

class DependencyScenarioController extends Controller
{
    public function __construct(
        protected DependencyScenarioServiceInterface $dependencyScenarioService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->dependencyScenarioService->listDependencyScenario();
    }


    /**
     * @param $uid
     *
     * @return mixed
     */
    public function detail($uid): mixed
    {
        return $this->dependencyScenarioService->getDependencyScenario($uid);
    }

    /**
     * @param CreateDependencyScenarioRequest $request
     *
     * @return mixed
     */
    public function create(CreateDependencyScenarioRequest $request): mixed
    {

        return $this->dependencyScenarioService->createDependencyScenario($request);
    }

    /**
     * @param UpdateDependencyScenarioRequest $request
     * @param                                 $uid
     *
     * @return mixed
     */
    public function update(UpdateDependencyScenarioRequest $request, $uid): mixed
    {
        return $this->dependencyScenarioService->updateDependencyScenario($request, $uid);
    }

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function delete($uid): mixed
    {

        return $this->dependencyScenarioService->deleteDependencyScenario($uid);
    }

}
