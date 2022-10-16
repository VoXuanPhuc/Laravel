<?php

namespace Encoda\Dependency\Validations;

use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Services\Interfaces\DependableServiceInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DependencyObjectValidation implements Rule
{
    public function __construct(
        protected DependableServiceInterface $dependableService
    )
    {
    }

    /**
     * @var string
     */
    protected string $errorMessage = 'Dependency object not found';


    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     * @throws ValidationException
     */
    public function passes($attribute, $value): bool
    {
        $this->validateDependencyType($value);
        //Try to get object, will throw error if object not exist
        return (bool) $this
            ->dependableService
            ->getDependableByTypeAndUid( DependableObjectTypeEnum::from($value['type']), $value['uid'] );
    }

    /**
     * @throws ValidationException
     */
    public function validateDependencyType(array $data)
    {
        Validator::make($data,[
            'type' => [
                'required',
                \Illuminate\Validation\Rule::in((array_column(DependableObjectTypeEnum::cases(), 'value')))
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
