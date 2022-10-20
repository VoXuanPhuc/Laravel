<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Dependency\Exports\DependencyScenarioExport;
use Encoda\Dependency\Http\Requests\Scenario\CreateDependencyScenarioRequest;
use Encoda\Dependency\Http\Requests\Scenario\UpdateDependencyScenarioRequest;
use Encoda\Dependency\Models\DependencyScenario;
use Encoda\Dependency\Repositories\Interfaces\DependencyScenarioRepositoryInterface;
use Encoda\Dependency\Services\Interfaces\DependableServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;
use Encoda\Dependency\Traits\DependencyScenarioServiceTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;


class DependencyScenarioService implements DependencyScenarioServiceInterface
{

    use DependencyScenarioServiceTrait;

    public function __construct(
        protected DependencyScenarioRepositoryInterface $dependencyScenarioRepository,
        protected DependableServiceInterface $dependableService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listDependencyScenario(): LengthAwarePaginator
    {
        return $this->dependencyScenarioRepository
            ->paginate(config('config.pagination_size'));
    }

    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getDependencyScenario(string $uid): mixed
    {
        /** @var DependencyScenario $dependencyScenario */
        $dependencyScenario = $this->dependencyScenarioRepository->findByUid( $uid );

        if (!$dependencyScenario) {
            throw new NotFoundException('Dependency scenario not found');
        }

        return $dependencyScenario->loadDependencies();
    }

    /**
     * @param CreateDependencyScenarioRequest $request
     * @return DependencyScenario
     * @throws ServerErrorException
     */
    public function createDependencyScenario( CreateDependencyScenarioRequest $request ): DependencyScenario
    {

        try {

            DB::beginTransaction();
            /** @var DependencyScenario $dependencyScenario */
            $dependencyScenario = $this->dependencyScenarioRepository->create($request->all());

            // Links dependencies
            $dependencies = $this->getDependencies( $request );

            $dependencyScenario->dependencies()->saveMany( $dependencies );

            DB::commit();
        }
        catch ( Throwable $e ) {

            DB::rollBack();
            Log::error( $e );
            throw new ServerErrorException('Oops! Create Dependency Scenario error');
        }

        return $dependencyScenario;

    }

    /**
     * @param UpdateDependencyScenarioRequest $request
     * @param string $uid
     * @return DependencyScenario
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function updateDependencyScenario(UpdateDependencyScenarioRequest $request, string $uid): DependencyScenario
    {
        /** @var DependencyScenario $dependencyScenario */
        $dependencyScenario = $this->getDependencyScenario($uid);

        try {

            // Update scenario
            $this->dependencyScenarioRepository->update($request->all(), $dependencyScenario->id);

            // Links dependencies
            $dependencies = $this->getDependencies( $request );

            // Delete existing
            $dependencyScenario->dependencies()->delete();

            // Save new in payload
            $dependencyScenario->dependencies()->saveMany( $dependencies );

            DB::commit();
        }
        catch ( Throwable $e ) {
            DB::rollBack();
            Log::error( $e );
            throw new ServerErrorException('Oops! Update Dependency Scenario error');
        }

        return $dependencyScenario->loadDependencies();
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

    /**
     * @param string $range
     * @return BinaryFileResponse
     */
    public function export($range = 'all')
    {
        $fileName = 'Dependency_Scenarios_'. time(). '.xlsx';

        $data = $this->dependencyScenarioRepository->with(['dependencies'])->all();

        $export = new DependencyScenarioExport( $data );

        return Excel::download( $export, $fileName );
    }
}
