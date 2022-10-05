<?php

namespace Encoda\Supplier\Http\Requests\Cert;

use Encoda\Core\Http\Requests\FormRequest;

class CreateSupplierCertRequest extends FormRequest
{
    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'certs' => 'required|array',
        ];
    }
}
