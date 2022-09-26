<?php

namespace Encoda\Organization\Http\Requests\Org;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Organization\Validations\OrganizationDomainUniqueValidation;

/**
 * @property $owner
 */
class UpdateOrganizationRequest extends FormRequest
{

    protected function rules(): array
    {

        return [
            'name' => 'required|min:2|max:255',
            'is_active' => 'required',
            'friendly_url' => ['required_if:landlord,==,false', new OrganizationDomainUniqueValidation( $this->uid ), ]

        ];

    }
}
