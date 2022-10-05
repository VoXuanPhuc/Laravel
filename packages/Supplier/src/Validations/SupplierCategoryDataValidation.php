<?php

namespace Encoda\Supplier\Validations;

use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

/**
 *
 */
class SupplierCategoryDataValidation implements Rule
{
    /**
     * @var string
     */
    protected string $errorMessage = 'Supplier category not found';

    /**
     * @param SupplierCategoryRepositoryInterface $supplierCategoryRepository
     */
    public function __construct(
        protected SupplierCategoryRepositoryInterface $supplierCategoryRepository
    )
    {
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (count($value) > 0) {
            return $this->supplierCategoryRepository->query()
                    ->whereIn('uid', collect($value)->pluck('uid')->toArray())
                    ->count() === count($value);
        }
        return true;

    }

    /**
     * @return array|string
     */
    public function message(): array|string
    {
        return $this->errorMessage;
    }
}
