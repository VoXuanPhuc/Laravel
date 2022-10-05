<?php

namespace Encoda\Supplier\Http\Controllers;

use Encoda\Supplier\Http\Requests\Cert\CreateSupplierCertRequest;
use Encoda\Supplier\Services\Interfaces\SupplierCertServiceInterface;

/**
 *
 */
class SupplierCertController extends Controller
{
    /**
     * @param SupplierCertServiceInterface $supplierCertService
     */
    public function __construct(
        protected SupplierCertServiceInterface $supplierCertService
    )
    {
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function getCertBySupplier(string $uid)
    {
        return $this->supplierCertService->getCertBySupplier($uid);
    }

    /**
     * @param CreateSupplierCertRequest $request
     * @param string                    $uid
     *
     * @return mixed
     */
    public function createCerts(CreateSupplierCertRequest $request, string $uid)
    {
        return $this->supplierCertService->createCerts($request, $uid);
    }

    /**
     * @param string $uid
     * @param string $certUID
     *
     * @return mixed
     */
    public function detailCert(string $uid, string $certUID )
    {
        return $this->supplierCertService->getDetailCert($uid, $certUID);
    }

    /**
     * @param string $uid
     * @param string $certUID
     *
     * @return mixed
     */
    public function deleteCert(string $uid, string $certUID)
    {
        return $this->supplierCertService->deleteCert($uid, $certUID);
    }
}
