<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
            'role_uids' => 'required',
            'is_remoted' => 'required',
            'utility_uids' => 'required',
            'remote_access_factor_uids' => 'required',
            'number_of_location' => 'required',
            'application_uids'  => 'required',
            'data_type' => 'required',
            'location' => 'required',
            'device_uids' => 'required',
        ];
    }
}
