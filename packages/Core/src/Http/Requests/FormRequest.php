<?php

namespace Encoda\Core\Http\Requests;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\Request;

abstract class FormRequest extends Request
{

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * @return string
     */
    protected function errorMessage(): string
    {
        return 'The given data was invalid.';
    }

    /**
     * @return int
     */
    protected function statusCode(): int
    {
        return HttpStatusCode::UNPROCESSABLE_ENTITY;
    }

    /**
     * @return JsonResponse|null
     */
    protected function errorResponse(): ?JsonResponse
    {
        return response()->json([
            'message' => $this->errorMessage(),
            'errors' => $this->validator->errors()->messages(),
        ], $this->statusCode());
    }

    /**
     * @throws AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new AuthorizationException();
    }

    /**
     * @throws ValidationException
     */
    protected function validationFailed(): void
    {
        throw new ValidationException($this->validator, $this->errorResponse());
    }

    /**
     *
     */
    protected function validationPassed()
    {
        //
    }

    /**
     * @return array
     * @throws ValidationException
     */
    public function validated(): array
    {
        return $this->validator->validated();
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function validate(): void
    {
        if (false === $this->authorize()) {
            $this->failedAuthorization();
        }

        $this->prepareForValidation();

        $this->validator = $this->app->make('validator')
            ->make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if ($this->validator->fails()) {
            $this->validationFailed();
        }

        $this->validationPassed();
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // no default action
    }

    /**
     * @param $app
     */
    public function setContainer($app)
    {
        $this->app = $app;
    }

    /**
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }

}
