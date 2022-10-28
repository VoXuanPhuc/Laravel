<?php

namespace Encoda\Core\Helpers;

use Closure;
use Encoda\Core\Enums\FilterTypes;
use Encoda\Core\Facades\Context;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class FilterFluent
 * @package App\Helpers
 */
class FilterFluent
{


    /**
     * @var Collection
     */
    private Collection $filters;

    /**
     * @var Collection
     */
    private Collection $originalFilters;

    /**
     * @var Collection
     */
    private Collection $customFilters;

    /**
     * @var array
     */
    private array $allowedFilters = array();

    /**
     * @var null
     */
    private $query;


    /**
     * @var string
     */
    private string $table = '';

    /**
     *
     */
    private function __construct()
    {
    }

    /**
     * @return FilterFluent
     */
    public static function init(): FilterFluent
    {
        $filterFluent = new self();
        $filterFluent->filters = collect();
        $filterFluent->originalFilters = collect();
        $filterFluent->customFilters = collect();
        $filterFluent->setFilterOptions(Context::request()->get());
        return $filterFluent;
    }


    /**
     * @param $query
     *
     * @return $this
     */
    public function setQuery($query): FilterFluent
    {
        $this->query = $query;
        return $this;
    }


    /**
     * @param $table
     *
     * @return $this
     */
    public function setTable($table): FilterFluent
    {
        $this->table = $table;
        return $this;
    }


    /**
     * @param array $options
     *
     * @return $this
     */
    public function setFilterOptions(array $options): FilterFluent
    {
        if (isset($options['filter'])) {
            $this->filters = collect($options['filter']);
            $this->originalFilters = collect($options['filter']);
        }
        return $this;
    }


    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setAllowedFilters(array $filters): FilterFluent
    {
        $this->allowedFilters = $filters;
        return $this;
    }


    /**
     * @param $columnFilter
     *
     * @return $this
     */
    public function removeFilter($columnFilter): FilterFluent
    {
        $this->filters = $this->filters->reject(function ($value) use ($columnFilter) {
            return is_array($columnFilter) ?
                in_array($value['name'], $columnFilter) :
                $value['name'] === $columnFilter;
        });
        return $this;
    }


    /**
     * @param string  $filterColumn
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCustomFilter(string $filterColumn, Closure $callback): FilterFluent
    {
        //Remove from original filter
        $this->removeFilter($filterColumn);
        $this->customFilters->push([$filterColumn, $callback]);
        return $this;
    }


    /**
     * @return $this
     * @throws ValidationException
     */
    public function validate(): FilterFluent
    {
        foreach ($this->filters as $filter) {
            if (!isset($filter['value'])) {
                throw ValidationException::withMessages(['filter[value]' => __('core::validation.required', ['attribute' => 'filter[value]'])]);
            }
            if (!empty($this->allowFilters) && !in_array($filter['name'], $this->allowFilters)) {
                throw ValidationException::withMessages(['filter[column]' => __('core::validation.invalid', ['attribute' => 'filter[column]'])]);
            }

            if (!isset($filter['name'])) {
                throw ValidationException::withMessages(['filter[column]' => __('core::validation.required', ['attribute' => 'filter[column]'])]);
            }
            if (isset($filter['type']) && !($filter['type'] instanceof FilterTypes)) {
                throw ValidationException::withMessages(['filter[type]' => __('core::validation.invalid', ['attribute' => 'filter[type]'])]);
            }
        }
        return $this;
    }


    /**
     * @param string $column
     *
     * @return void
     * @throws ValidationException
     */
    public function validateColumn(string $column): void
    {
        if (!in_array($column, $this->allowedFilters)) {
            throw ValidationException::withMessages(
                ['filter[column]' => __('core::validation.invalid', ['attribute' => $column])]
            );
        }
    }


    /**
     * @return void
     * @throws ValidationException
     */
    public function applyFilter(): void
    {
        foreach ($this->filters as $filter) {
            $this->validateColumn($filter['name']);
            self::applyBasicFilter($this->query, $filter['type'], $filter['name'], $filter['value'], $this->table);
        }

        foreach ($this->customFilters as [$column, $callback]) {
            $filters = $this->getFilters($column);
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    $this->validateColumn($filter['name']);
                    $callback($this->query, $filter['type'], $filter['name'], $filter['value']);
                }
            }
        }
    }

    /**
     * @param        Builder $query
     * @param        $type
     * @param        $column
     * @param        $value
     * @param string $tableName
     *
     * @return void
     */
    public static function applyBasicFilter($query, $type, $column, $value, string $tableName = ''): void
    {
        $column = $tableName === '' ? $column : $tableName . "." . $column;
        switch ($type) {
            case FilterTypes::IS_NOT:
                $query->where($column, '!=', $value);
                break;
            case FilterTypes::CONTAIN:
                $query->where($column, 'LIKE', "%" . $value . "%");
                break;
            case FilterTypes::IS:
                $query->where($column, '=', $value);
                break;
            case FilterTypes::LESS_THAN:
                $query->where($column, '<', $value);
                break;
            case FilterTypes::GREATER_THAN:
                $query->where($column, '>', $value);
                break;
            case FilterTypes::LESS_THAN_OR_EQUAL:
                $query->where($column, '<=', $value);
                break;
            case FilterTypes::GREATER_THAN_OR_EQUAL:
                $query->where($column, '>=', $value);
                break;
            case FilterTypes::IN:
                $arrayValue = array_map('trim', explode(',', $value));
                $query->whereIn($column, array_filter($arrayValue));
                break;
            case FilterTypes::BETWEEN:
                $arrayValue = array_map('trim', explode(',', $value));
                $query->whereBetween($column, array_filter($arrayValue));
                break;
            case FilterTypes::BOOLEAN:
                $query->where($column, '=', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                break;
            default:
        }
    }

    /**
     * @param        $query
     * @param        $type
     * @param        $column1
     * @param        $column2
     * @param        $value
     * @param string $tableName
     *
     * @return void
     */
    public static function applyCombinedColumnFilter($query, $type, $column1, $column2, $value, string $tableName = ''): void
    {
        $column1 = $tableName === '' ? $column1 : $tableName . "." . $column1;
        $column2 = $tableName === '' ? $column2 : $tableName . "." . $column2;

        // Remove commas and multi spaces
        $value = preg_replace('/,/', '', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        switch ($type) {
            case FilterTypes::IS_NOT:
                $query->where(DB::raw("CONCAT($column1, ' ', $column2)"), '!=', $value);
                break;
            case FilterTypes::CONTAIN:
                $query->where(DB::raw("CONCAT($column1, ' ', $column2)"), 'LIKE', "%" . $value . "%");
                break;
            case FilterTypes::IS:
                $query->where(DB::raw("CONCAT($column1, ' ', $column2)"), '=', $value);
                break;
            default:
        }
    }

    /**
     * @param $filterColumn
     *
     * @return array
     */
    private function getFilters($filterColumn): array
    {
        return $this->originalFilters->filter(function ($item) use ($filterColumn) {
            return $item['name'] === $filterColumn;
        })->toArray();
    }
}
