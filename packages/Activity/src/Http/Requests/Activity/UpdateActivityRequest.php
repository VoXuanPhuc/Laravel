<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Activity\Validations\ActivityDivisionOrBusinessUnitRequiredValidation;
use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $assignee
 * @property $roles
 * @property $alternative_roles
 * @property $utilities
 * @property $division
 * @property $business_unit
 */
class UpdateActivityRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
            'roles' => 'required',
            'alternative_roles' => 'nullable',
            'division' => [ new ActivityDivisionOrBusinessUnitRequiredValidation( $this->business_unit )]
//            'is_remoted' => 'required|numeric',
//            'status' => 'required|numeric|min:1|max:3',
//            'step' => 'required|numeric|min:1|max:3',
//            'utility_uids' => 'required',
//            'remote_access_factor_uids' => 'required',
//            'number_of_location' => 'required|numeric',
//            'application_uids'  => 'required',
//            'data_type' => 'required',
//            'location' => 'required',
//            'device_uids' => 'required',
        ];
    }
}
