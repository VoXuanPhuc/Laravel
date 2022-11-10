<?php

namespace Encoda\Notification\Http\Requests\EventNotification;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Notification\Enums\EventNotificationMethodEnum;
use Encoda\Notification\Enums\EventNotificationRuleActionEnum;
use Encoda\Notification\Enums\EventNotificationRuleModelEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Illuminate\Validation\Rule;

/**
 * @property $owner
 * @property $industries
 */
class CreateEventNotificationRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name'  => 'required|string|max:511',
            'title' => 'required|string|max:511',
            'type'  => ['required',
                        'string',
                        Rule::in(values: array_column(EventNotificationTypeEnum::cases(), 'value'))
            ],

            'data'           => 'required|string',
            'description'    => 'string',
            'pinned'         => 'required|boolean',
            'dispatch_after' => 'after:now|nullable',
            'is_active'      => 'required|boolean',
            'all_user'       => 'filled|boolean',
            'methods'        => ['required', 'array'],
            'methods.*'      => ['required',
                                 Rule::in(values: array_column(EventNotificationMethodEnum::cases(), 'value'))
            ],
            'rules'          => ['array',
                                 Rule::requiredIf(request()->get('type') === EventNotificationTypeEnum::AUTO->value),
            ],
            'rules.*.model'  => ['string',
                                 Rule::requiredIf(request()->get('type') === EventNotificationTypeEnum::AUTO->value),
                                 Rule::in(values: array_column(EventNotificationRuleModelEnum::cases(), 'value'))
            ],
            'rules.*.action' => ['string',
                                 Rule::requiredIf(request()->get('type') === EventNotificationTypeEnum::AUTO->value),
                                 Rule::in(values: array_column(EventNotificationRuleActionEnum::cases(), 'value'))
            ],
            'receivers'       => ['array',
                                 Rule::requiredIf(!request()->get('all_user')),
            ],
            'receivers.*.uid' => [Rule::requiredIf(!request()->get('all_user')), 'string'],
            'attachments'    => ['array'],
            'attachments.*.uid'    => ['required_with:attachments']
        ];
    }
}
