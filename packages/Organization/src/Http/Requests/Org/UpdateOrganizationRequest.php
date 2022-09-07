<?php

namespace Encoda\Organization\Http\Requests\Org;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $owner
 */
class UpdateOrganizationRequest extends FormRequest
{

    protected function rules(): array
    {
        return [];
    }
}
