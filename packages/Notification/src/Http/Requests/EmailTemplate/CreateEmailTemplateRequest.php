<?php
namespace Encoda\Notification\Http\Requests\EmailTemplate;

use Encoda\Core\Http\Requests\FormRequest;

class CreateEmailTemplateRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:511',
            'description' => 'string',
            'title' => 'required|string|max:511',
            'data'  => 'required|string',
            'attachments'    => ['array'],
            'attachments.*.uid'    => ['required_with:attachments']
        ];
    }
}
