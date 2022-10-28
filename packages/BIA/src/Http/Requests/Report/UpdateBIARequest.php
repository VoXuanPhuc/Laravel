<?php

namespace Encoda\BIA\Http\Requests\Report;

use Encoda\BIA\Enums\BIAStatusEnum;
use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 */
class UpdateBIARequest extends FormRequest
{

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
//            'description'  => 'string',
            'status'       => [Rule::in((array_column(BIAStatusEnum::cases(), 'value')))],
            'due_date'     => 'required|after:now',
            'reports'      => 'array|filled',
            'reports.*.uid' => 'required'
        ];
    }
}
