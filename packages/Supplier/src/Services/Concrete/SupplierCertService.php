<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Encoda\Supplier\Http\Requests\Cert\CreateSupplierCertRequest;
use Encoda\Supplier\Services\Interfaces\SupplierCertServiceInterface;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;

/**
 *
 */
class SupplierCertService implements SupplierCertServiceInterface
{
    /**
     * @param SupplierServiceInterface $supplierService
     * @param DocumentServiceInterface $documentService
     */
    public function __construct(
        protected SupplierServiceInterface        $supplierService,
        protected DocumentServiceInterface        $documentService
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
            ->getDocuments('certs');
    }

    /**
     * @param string $supplierUID
     * @param string $certUID
     *
     * @return boolean
     * @throws NotFoundException
     */
    public function deleteCert(string $supplierUID, string $certUID)
    {
        $supplier = $this->supplierService
            ->getSupplier($supplierUID)
            ->getDocumentsQuery('certs')
            ->hasUID($certUID)
            ->first();
        return $supplier->delete();
    }
}
