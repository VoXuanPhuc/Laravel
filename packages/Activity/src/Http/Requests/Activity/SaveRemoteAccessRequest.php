<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $remote_access_factors
 */
class SaveRemoteAccessRequest extends FormRequest
{


    protected function rules(): array
    {
        return [];
    }
}
