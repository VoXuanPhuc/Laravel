<?php

namespace Encoda\Dependency\Validations;

use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Services\Interfaces\DependencyDetailServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyServiceInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DependencyObjectValidation implements Rule
{
    public function __construct(
        protected DependencyDetailServiceInterface $dependencyDetailService
    )
    {
    }

    /**
     * @var string
     */
    protected string $errorMessage = 'Dependency object not found';


    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->validateDependencyType($value);
        //Try to get object, will throw error if object not exist
        return (bool) $this
            ->dependencyDetailService
            ->getDependencyObject(DependencyObjectTypes::from($value['type']), $value['uid']);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateDependencyType(array $data)
    {
        Validator::make($data,[
            'type' => [
                'required',
                \Illuminate\Validation\Rule::in((array_column(DependencyObjectTypes::cases(), 'value')))
            ],
        ])->validate();
    }

    /**
     * @return array|string
     */
    public function message(): array|string
    {
        return $this->errorMessage;
    }
}
