<?php

namespace Encoda\Supplier\Http\Requests\Supplier;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Supplier\Validations\SupplierCategoryDataValidation;

class UpdateSupplierRequest extends FormRequest
{

    /**
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'name' => 'filled|string|max:255',
            'address' => 'string|max:255',
            'description' => 'string|max:1023',
            'email' => 'email|max:255',
            'fax' => 'string|max:255',
            'phone_number' => 'string|max:15',
            'is_active' => 'required|boolean',
            'categories' => ['array', app(SupplierCategoryDataValidation::class)],
            'certs' => 'array'
        ];
    }
}
