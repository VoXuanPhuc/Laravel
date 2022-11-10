<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $dependency_scenarios[]
 * @property $suppliers[]
 */
class SaveDependenciesAndSuppliersRequest extends FormRequest
{

    protected function rules(): array
    {
        return [];
    }
}
