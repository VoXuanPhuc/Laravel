<?php


namespace Encoda\Organization\Http\Requests\Industry;

use Encoda\Organization\Models\Industry;

class IndustryRequest extends \Encoda\Core\Http\Requests\FormRequest
{
    /**
     * custom error message
     * @return array
     */
    protected function messages(): array
    {

        return [
            'name.unique' => Industry::NAME_UNIQUE_CODE
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:industries,name,'. $this->uid . ',uid',
            'description' => 'max:255'
        ];
    }
}
