<?php

namespace Encoda\Supplier\Http\Requests\Supplier;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Supplier\Validations\SupplierCategoryDataValidation;

class CreateSupplierRequest extends FormRequest
{
    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'string|max:255',
            'description' => 'string|max:1023',
            'email' => 'email|max:255',
            'phone_number' => 'string|max:15',
            'is_active' => 'required|boolean',
            'categories' => ['required','array', app(SupplierCategoryDataValidation::class)],
            'certs'     => 'array'
        ];
    }
}
