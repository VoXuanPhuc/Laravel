<?php

namespace Encoda\Dependency\Http\Requests\Scenario;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateDependencyScenarioRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'filled|max:255',
            'description' => 'string|max:1023',
            'is_active' => 'required|boolean'
        ];
    }
}
