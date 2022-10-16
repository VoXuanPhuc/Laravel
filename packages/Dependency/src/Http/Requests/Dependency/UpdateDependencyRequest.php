<?php

namespace Encoda\Dependency\Http\Requests\Dependency;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Enums\DependableTypeEnum;
use Illuminate\Validation\Rule;

class UpdateDependencyRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'object'                              => 'required',
            'object.type'                         => [
                'required',
                Rule::in((array_column(DependableObjectTypeEnum::cases(), 'value')))
            ],
            'description'                         => 'string|max:1023',
            'dependency_details'                  => 'required|array',
            'dependency_details.*.dependent_type' => [
                'required',
                Rule::in((array_column(DependableTypeEnum::cases(), 'value')))
            ],
        ];
    }
}
