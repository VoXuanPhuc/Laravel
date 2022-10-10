<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Http\Requests\Dependency\CreateDependencyRequest;
use Encoda\Dependency\Http\Requests\Dependency\UpdateDependencyRequest;
use Encoda\Dependency\Models\Dependency;
use Encoda\Dependency\Repositories\Interfaces\DependencyRepositoryInterface;
use Encoda\Dependency\Services\Interfaces\DependencyDetailServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 *
 */
class DependencyService implements DependencyServiceInterface
{
    /**
     * @param DependencyRepositoryInterface      $dependencyRepository
     * @param DependencyScenarioServiceInterface $dependencyScenarioService
     * @param DependencyDetailServiceInterface   $dependencyDetailService
     */
    public function __construct(
        protected DependencyRepositoryInterface      $dependencyRepository,
        protected DependencyScenarioServiceInterface $dependencyScenarioService,
        protected DependencyDetailServiceInterface   $dependencyDetailService
    )
    {
    }

    /**
     * @param string $scenarioUID
     *
     * @return mixed
     */
    public function listDependency(string $scenarioUID)
    {
        return $this->dependencyScenarioService
            ->getDependencyScenario($scenarioUID)
            ->dependencies()
            ->get();
    }

    /**
     * @param string $scenarioUID
     * @param string $uid
     *
     * @return Dependency
     * @throws NotFoundException
     */
    public function getDependency(string $scenarioUID, string $uid)
    {
        $dependency = $this->dependencyScenarioService
            ->getDependencyScenario($scenarioUID)
            ->dependencies()
            ->hasUID($uid)
            ->get()
            ->first();
        if (!$dependency) {
            throw new NotFoundException('Dependency not found');
        }
        return $dependency;
    }


    /**
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function createDependency(CreateDependencyRequest $request, string $scenarioUID)
    {
        try {
            DB::beginTransaction();

            $dependencyScenario = $this->dependencyScenarioService->getDependencyScenario($scenarioUID);
            $objectData = $request->get("object");
            $targetObject = $this
                ->dependencyDetailService
                ->getDependencyObject(DependencyObjectTypes::from($objectData['type']), $objectData['uid']);
            $dependency = $this->dependencyRepository->newInstance([
                'organization_id' => tenant()->id,
                'name'            => $request->get('description'),
            ]);
            /**
             * @var Dependency $dependency
             */

            $dependency->object()->associate($targetObject);
            $dependency->dependencyScenario()->associate($dependencyScenario);
            $dependency->save();

            foreach ($request->get('dependency_details') as $dependencyDetail) {
                $this->dependencyDetailService->createDependencyDetail($dependency, $dependencyDetail);
            }
            DB::commit();

        } catch (NotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Update dependency error');
        }


        return $dependency->fresh();
    }

    /**
     * @param UpdateDependencyRequest $request
     * @param string                  $scenarioUID
     * @param string                  $uid
     *
     * @return Dependency|null
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function updateDependency(UpdateDependencyRequest $request, string $scenarioUID, string $uid)
    {
        try {
            DB::beginTransaction();

            $objectData = $request->get("object");
            $targetObject = $this
                ->dependencyDetailService
                ->getDependencyObject(DependencyObjectTypes::from($objectData['type']), $objectData['uid']);
            $dependency = $this->getDependency($scenarioUID, $uid);
            $dependency->object()->associate($targetObject);
            $dependency->save();
            $dependency->dependencyDetails()->delete();
            foreach ($request->get('dependency_details') as $dependencyDetail) {
                $this->dependencyDetailService->createDependencyDetail($dependency, $dependencyDetail);
            }
            DB::commit();

        } catch (NotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Update dependency error');
        }


        return $dependency->fresh();
    }

    /**
     * @param string $scenarioUID
     * @param string $uid
     *
     * @return bool|null
     * @throws NotFoundException
     */
    public function deleteDependency(string $scenarioUID, string $uid)
    {
        return $this->getDependency($scenarioUID, $uid)->delete();
    }


}
