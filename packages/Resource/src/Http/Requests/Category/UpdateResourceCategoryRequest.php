<?php

namespace Encoda\Resource\Http\Requests\Category;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateResourceCategoryRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:255'
        ];
    }
}
