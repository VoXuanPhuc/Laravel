<?php

namespace Encoda\Organization\Http\Requests\Org;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $owner
 * @property $industries
 */
class CreateOrganizationRequest extends FormRequest
{

    protected function rules(): array
    {
        return [];
    }
}
