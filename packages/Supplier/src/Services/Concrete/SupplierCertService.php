<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Supplier\Http\Requests\Cert\CreateSupplierCertRequest;
use Encoda\Supplier\Repositories\Interfaces\SupplierCertRepositoryInterface;
use Encoda\Supplier\Services\Interfaces\SupplierCertServiceInterface;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;

/**
 *
 */
class SupplierCertService implements SupplierCertServiceInterface
{
    /**
     * @param SupplierServiceInterface        $supplierService
     * @param SupplierCertRepositoryInterface $supplierCertRepository
     */
    public function __construct(
        protected SupplierServiceInterface        $supplierService,
        protected SupplierCertRepositoryInterface $supplierCertRepository
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
        return $this->supplierService
            ->getSupplier($uid)
            ->certs()
            ->get();
    }

    /**
     * @param CreateSupplierCertRequest $request
     * @param string                    $uid
     *
     * @return \Illuminate\Support\Collection
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createCerts(CreateSupplierCertRequest $request, string $uid)
    {
        $supplier = $this->supplierService
            ->getSupplier($uid);
        $certs = [];
        foreach ($request->get('certs') as $certPath) {
            $cert = $this->supplierCertRepository->create([
                'cert_path'   => $certPath,
                'supplier_id' => $supplier->id
            ]);
            $certs[] = $cert->refresh();
        }
        return collect($certs);
    }

    /**
     * @param string $supplierUID
     * @param string $certUID
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getDetailCert(string $supplierUID, string $certUID)
    {
        $cert = $this->supplierService
            ->getSupplier($supplierUID)
            ->certs()
            ->hasUID($certUID)
            ->get()->first();

        if (!$cert) {
            throw new NotFoundException('Supplier cert not found');
        }
        return $cert;
    }

    /**
     * @param string $supplierUID
     * @param string $certUID
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function deleteCert(string $supplierUID, string $certUID)
    {
        $cert = $this->getDetailCert($supplierUID, $certUID);
        return $cert
            ->delete();
    }
}
