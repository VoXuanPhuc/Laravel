<?php

namespace Encoda\BCP\Http\Controllers;

use Encoda\BCP\Http\Requests\Report\CreateBCPRequest;
use Encoda\BCP\Http\Requests\Report\UpdateBCPRequest;
use Encoda\BCP\Services\Interfaces\BCPExportingServiceInterface;
use Encoda\BCP\Services\Interfaces\BCPServiceInterface;

/**
 *
 */
class BCPController extends Controller
{
    /**
     * @param BCPServiceInterface $bcpService
     * @param BCPExportingServiceInterface $bcpExportingService
     */
    public function __construct(
        protected BCPServiceInterface $bcpService,
        protected BCPExportingServiceInterface $bcpExportingService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->bcpService->list();
    }

    /**
     * @return mixed
     */
    public function top(): mixed
    {
        return $this->bcpService->top();
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function detail(string $uid): mixed
    {
        return $this->bcpService->getBCP($uid);
    }

    /**
     * @param CreateBCPRequest $request
     *
     * @return mixed
     */
    public function create(CreateBCPRequest $request): mixed
    {
        return $this->bcpService->create($request);
    }

    /**
     * @param UpdateBCPRequest $request
     * @param string           $uid
     *
     * @return mixed
     */
    public function update(UpdateBCPRequest $request, string $uid): mixed
    {
        return $this->bcpService->update($request, $uid);
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function delete(string $uid): mixed
    {
        return $this->bcpService->delete($uid);
    }


    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function export(string $uid): mixed
    {
        return $this->bcpExportingService->exportRecord( $uid );
    }

    /**
     * @return mixed
     */
    public function exportAll(): mixed
    {
        return $this->bcpExportingService->exportAll();
    }


}
