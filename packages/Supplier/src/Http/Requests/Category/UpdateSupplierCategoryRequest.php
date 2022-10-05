<?php

namespace Encoda\Supplier\Http\Requests\Category;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateSupplierCategoryRequest extends FormRequest
{

    /**
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'name' => 'string|unique:supplier_categories|max:255|filled',
            'description' => 'string|max:1023'
        ];
    }
}
