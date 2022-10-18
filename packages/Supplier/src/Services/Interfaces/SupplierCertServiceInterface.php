<?php

namespace Encoda\Supplier\Services\Interfaces;

use Encoda\Supplier\Http\Requests\Cert\CreateSupplierCertRequest;

interface SupplierCertServiceInterface
{
    public function getCertBySupplier(string $uid);
    public function deleteCert(string $supplierUID,string $certUID);
}
