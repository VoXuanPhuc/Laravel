<?php

namespace Encoda\Dependency\Http\Requests\Dependency;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Enums\DependencyTypes;
use Illuminate\Validation\Rule;

class CreateDependencyRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'object'                              => 'required',
            'object.type'                         => [
                'required',
                Rule::in((array_column(DependencyObjectTypes::cases(), 'value')))
            ],
            'description'                         => 'string|max:1023',
            'dependency_details'                  => 'required|array',
            'dependency_details.*.dependent_type' => [
                'required',
                Rule::in((array_column(DependencyTypes::cases(), 'value')))
            ],
        ];
    }
}
