<?php

namespace Encoda\Supplier\Http\Requests\Category;

use Encoda\Core\Http\Requests\FormRequest;

class CreateSupplierCategoryRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|unique:supplier_categories|max:255',
            'description' => 'string|max:1023'
        ];
    }
}
