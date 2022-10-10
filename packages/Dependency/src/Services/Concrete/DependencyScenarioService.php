<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Dependency\Http\Requests\Scenario\CreateDependencyScenarioRequest;
use Encoda\Dependency\Http\Requests\Scenario\UpdateDependencyScenarioRequest;
use Encoda\Dependency\Repositories\Interfaces\DependencyScenarioRepositoryInterface;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;
use Encoda\Organization\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;


class DependencyScenarioService implements DependencyScenarioServiceInterface
{
    public function __construct(
        protected DependencyScenarioRepositoryInterface $dependencyScenarioRepository
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    public function listDependencyScenario()
    {
        return tenant()
            ->dependencyScenarios()
            ->paginate(config('config.pagination_size'));
    }

    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getDependencyScenario(string $uid)
    {
        $dependency = $this->dependencyScenarioRepository->query()
            ->hasUID($uid)
            ->get()
            ->first();

        if (!$dependency) {
            throw new NotFoundException('Dependency scenario not found');
        }

        return $dependency;
    }


    /**
     * @param CreateDependencyScenarioRequest $request
     *
     * @return null
     * @throws ServerErrorException
     */
    public function createDependencyScenario(CreateDependencyScenarioRequest $request)
    {

        try {
            DB::beginTransaction();
            /** @var Resource $resource */
            $dependencyScenario = $this->dependencyScenarioRepository->create($request->all());

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create Dependency Scenario error');
        }

        return $dependencyScenario?->refresh();
    }

    /**
     * @param UpdateDependencyScenarioRequest $request
     * @param string                          $uid
     *
     * @return null
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function updateDependencyScenario(UpdateDependencyScenarioRequest $request, string $uid)
    {
        $dependencyScenario = $this->getDependencyScenario($uid);

        return ($this->dependencyScenarioRepository->update($request->all(), $dependencyScenario->id))?->refresh();
    }

    /**
     * @param string $uid
     *
     * @return int
     * @throws NotFoundException
     */
    public function deleteDependencyScenario(string $uid)
    {
        $dependencyScenario = $this->getDependencyScenario($uid);

        return $this->dependencyScenarioRepository->delete($dependencyScenario->id);
    }
}
