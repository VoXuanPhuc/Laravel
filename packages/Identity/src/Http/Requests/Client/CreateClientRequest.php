<?php

namespace Encoda\Identity\Http\Requests\Client;

use Encoda\Core\Http\Requests\FormRequest;

class CreateClientRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'clientName' => 'required',
        ];
    }
}
