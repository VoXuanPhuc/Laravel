<?php

namespace Encoda\Supplier\Services\Interfaces;

use Encoda\Supplier\Http\Requests\Cert\CreateSupplierCertRequest;

interface SupplierCertServiceInterface
{
    public function getCertBySupplier(string $uid);
    public function createCerts(CreateSupplierCertRequest $request,string $uid);
    public function getDetailCert(string $supplierUID,string $certUID);
    public function deleteCert(string $supplierUID,string $certUID);
}
