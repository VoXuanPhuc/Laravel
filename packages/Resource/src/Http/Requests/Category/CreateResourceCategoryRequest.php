<?php

namespace Encoda\Resource\Http\Requests\Category;

use Encoda\Core\Http\Requests\FormRequest;

class CreateResourceCategoryRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|unique:resource_categories|max:255'
        ];
    }
}
