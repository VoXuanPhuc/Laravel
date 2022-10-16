<?php

namespace Encoda\Dependency\Http\Controllers;

use Encoda\Dependency\Services\Interfaces\DependencyFactorServiceInterface;

class DependencyFactorController extends Controller
{
    public function __construct(
        protected DependencyFactorServiceInterface $dependencyFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->dependencyFactorService->getDependencyFactors();
    }


}
