<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $applications[]
 * @property $equipments[]
 * @property $it_solution
 */
class SaveSoftwareAndEquipmentRequest extends FormRequest
{


    protected function rules(): array
    {
        return [];
    }
}
