<?php

namespace Encoda\Organization\Http\Requests\Org;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Organization\Validations\OrganizationDomainUniqueValidation;

/**
 * @property $owner
 * @property $industries
 */
class CreateOrganizationRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255|unique:organizations',
            'is_active' => 'required',
            'friendly_url' => ['required', new OrganizationDomainUniqueValidation()]

        ];
    }
}
