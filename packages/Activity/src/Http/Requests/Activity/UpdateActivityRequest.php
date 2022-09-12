<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateActivityRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
            'role_ids' => 'required',
            'is_remoted' => 'required',
            'utility_ids' => 'required',
            'remote_access_ids' => 'required',
            'number_of_location' => 'required',
            'application_ids'  => 'required',
            'data_type' => 'required',
            'storage' => 'required',
            'device_ids' => 'required',
        ];
    }
}
