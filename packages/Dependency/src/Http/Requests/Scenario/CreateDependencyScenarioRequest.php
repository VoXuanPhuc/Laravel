<?php

namespace Encoda\Dependency\Http\Requests\Scenario;

use Encoda\Core\Http\Requests\FormRequest;

class CreateDependencyScenarioRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'string|max:1023',
        ];
    }
}
