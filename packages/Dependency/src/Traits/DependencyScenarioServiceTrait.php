<?php

namespace Encoda\Dependency\Traits;

use Encoda\Activity\Models\Activity;
use Encoda\Dependency\Enums\DependableTypeEnum;
use Encoda\Dependency\Http\Requests\Scenario\CreateDependencyScenarioRequest;
use Encoda\Dependency\Http\Requests\Scenario\UpdateDependencyScenarioRequest;
use Encoda\Dependency\Services\Interfaces\DependableServiceInterface;
use Encoda\Resource\Models\Resource;
use Encoda\Supplier\Models\Supplier;

/**
 * @property DependableServiceInterface $dependableService
 */
trait DependencyScenarioServiceTrait
{

    /**
     * @var array
     */
    protected array $dependencyWorkflowIterator = [
        'targets' => DependableTypeEnum::TARGET,
        'upstream' => DependableTypeEnum::UPSTREAM,
        'downstream' => DependableTypeEnum::DOWNSTREAM,
    ];



    /**
     * This is get dependable objects ( Activity|Resource|Supplier )
     * @param $inputDependencies
     * @return array
     */
    private function getDependableObjectReferences($inputDependencies): array
    {

        $dependenciesByType = [];
        $result = [];

        foreach ( $inputDependencies as $inputDependency ) {
            $dependenciesByType[$inputDependency['type']][] = $inputDependency['uid'];
        }

        foreach ( $dependenciesByType as $type => $uids ) {
            $result = array_merge(
                $result,
                $this->dependableService->getDependableByTypeAndUids( $type, $uids )->all()
            );
        }

        return $result;
    }

    /**
     * @param $inputDependencies
     * @param $type
     * @return array
     */
    protected function getDependenciesByWorkflow( $inputDependencies, $type ): array
    {

        $dependableObjs = $this->getDependableObjectReferences( $inputDependencies );

        return array_map( function ( $item ) use ( $type ) {

            /** @var Activity|Resource|Supplier $item */
            return $item->toDependency( $type );
        }, $dependableObjs );

    }

    /**
     * @param CreateDependencyScenarioRequest|UpdateDependencyScenarioRequest $request
     * @return array
     */
    public function getDependencies( CreateDependencyScenarioRequest| UpdateDependencyScenarioRequest $request ): array
    {

        $dependencies = [];

        foreach ( $this->dependencyWorkflowIterator as $requestKey => $workflowEnum ) {
            $dependenciesByWorkflow = $this->getDependenciesByWorkflow( $request->{$requestKey}, $workflowEnum->value );
            $dependencies = array_merge( $dependencies, $dependenciesByWorkflow );
        }

        return $dependencies;
    }
}
