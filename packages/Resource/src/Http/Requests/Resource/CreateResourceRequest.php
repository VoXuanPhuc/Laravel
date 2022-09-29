<?php

namespace Encoda\Resource\Http\Requests\Resource;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Resource\Validations\ResourceCategoryDataValidation;
use Encoda\Resource\Validations\ResourceOwnerArrayValidation;

class CreateResourceRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|unique:resources|max:255',
            'status' => 'required|numeric',
            'category' => ['required',  new ResourceCategoryDataValidation()],
            'owners' => ['required', new ResourceOwnerArrayValidation() ]
        ];
    }
}
