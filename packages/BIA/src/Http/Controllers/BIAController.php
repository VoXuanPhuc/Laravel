<?php

namespace Encoda\BIA\Http\Controllers;

use Encoda\BIA\Http\Requests\Report\CreateBIARequest;
use Encoda\BIA\Http\Requests\Report\UpdateBIARequest;
use Encoda\BIA\Services\Interfaces\BIAExportingServiceInterface;
use Encoda\BIA\Services\Interfaces\BIAServiceInterface;

/**
 *
 */
class BIAController extends Controller
{
    /**
     * @param BIAServiceInterface $biaService
     * @param BIAExportingServiceInterface $biaExportingService
     */
    public function __construct(
        protected BIAServiceInterface $biaService,
        protected BIAExportingServiceInterface $biaExportingService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->biaService->list();
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function detail(string $uid): mixed
    {
        return $this->biaService->getBIA($uid);
    }

    /**
     * @param CreateBIARequest $request
     *
     * @return mixed
     */
    public function create(CreateBIARequest $request): mixed
    {
        return $this->biaService->create($request);
    }

    /**
     * @param UpdateBIARequest $request
     * @param string           $uid
     *
     * @return mixed
     */
    public function update(UpdateBIARequest $request, string $uid): mixed
    {
        return $this->biaService->update($request, $uid);
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function export(string $uid): mixed
    {
        return $this->biaExportingService->exportRecord( $uid );
    }

    /**
     * @return mixed
     */
    public function exportAll(): mixed
    {
        return $this->biaExportingService->exportAll();
    }


    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function delete(string $uid): mixed
    {
        return $this->biaService->delete($uid);
    }

}
