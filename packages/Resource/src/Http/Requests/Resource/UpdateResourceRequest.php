<?php

namespace Encoda\Resource\Http\Requests\Resource;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Resource\Validations\ResourceCategoryDataValidation;
use Encoda\Resource\Validations\ResourceOwnerArrayValidation;

class UpdateResourceRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'status' => 'nullable|numeric'
        ];
    }
}
