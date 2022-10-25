<?php

namespace Encoda\BCP\Http\Requests\Report;

use Encoda\BCP\Enums\BCPStatusEnum;
use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 */
class UpdateBCPRequest extends FormRequest
{

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
//            'description'  => 'string',
            'status'       => [Rule::in((array_column(BCPStatusEnum::cases(), 'value')))],
            'due_date'     => 'required|after:now',
            'reports'      => 'array|filled',
            'reports.*.uid' => 'required'
        ];
    }
}
