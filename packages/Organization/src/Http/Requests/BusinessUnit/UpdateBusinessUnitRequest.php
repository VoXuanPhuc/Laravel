<?php

namespace Encoda\Organization\Http\Requests\BusinessUnit;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $division[]
 */
class UpdateBusinessUnitRequest extends FormRequest
{

    protected function rules(): array
    {
        return [];
    }
}
