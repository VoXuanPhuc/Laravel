<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

class CreateActivityRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
            'role_uids' => 'required',
            'alternative_role_uids' => 'nullable',
            'is_remoted' => 'required|numeric',
            'status' => 'required|numeric|min:1|max:3',
            'step' => 'required|numeric|min:1|max:3',
            'utility_uids' => 'required',
            'remote_access_factor_uids' => 'required',
            'number_of_location' => 'required|numeric',
            'application_uids'  => 'required',
            'data_type' => 'required',
            'location' => 'required',
            'device_uids' => 'required',
        ];
    }
}
