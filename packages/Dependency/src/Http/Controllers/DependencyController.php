<?php

namespace Encoda\Dependency\Http\Controllers;

use Encoda\Dependency\Http\Requests\Dependency\CreateDependencyRequest;
use Encoda\Dependency\Http\Requests\Dependency\UpdateDependencyRequest;
use Encoda\Dependency\Services\Interfaces\DependencyServiceInterface;

class DependencyController extends Controller
{
    public function __construct(
        protected DependencyServiceInterface $dependencyService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(string $scenarioUID ): mixed
    {
        return $this->dependencyService->listDependency($scenarioUID);
    }


    /**
     * @param string $scenarioUID
     * @param string $uid
     *
     * @return mixed
     */
    public function detail(string $scenarioUID, string $uid): mixed
    {
        return $this->dependencyService->getDependency($scenarioUID, $uid);
    }

    /**
     * @param CreateDependencyRequest $request
     *
     * @return mixed
     */
    public function create(string $scenarioUID, CreateDependencyRequest $request): mixed
    {

        return $this->dependencyService->createDependency($request, $scenarioUID);
    }

    /**
     * @param UpdateDependencyRequest $request
     * @param                         $uid
     *
     * @return mixed
     */
    public function update(UpdateDependencyRequest $request, string $scenarioUID, $uid): mixed
    {
        return $this->dependencyService->updateDependency($request, $scenarioUID , $uid);
    }

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function delete(string $scenarioUID, string $uid): mixed
    {

        return $this->dependencyService->deleteDependency($scenarioUID, $uid);
    }
}
