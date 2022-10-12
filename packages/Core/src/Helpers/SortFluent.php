<?php

namespace Encoda\Core\Helpers;

use Closure;
use Encoda\Core\Enums\SortTypes;
use Encoda\Core\Facades\Context;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 *
 */
class SortFluent
{


    /**
     * @var Collection
     */
    private Collection $sorts;

    /**
     * @var Collection
     */
    private Collection $originalSorts;

    /**
     * @var Collection
     */
    private Collection $customSorts;

    /**
     * @var Collection
     */
    private Collection $allowedSorts ;

    /**
     * @var null
     */
    private $query = null;

    /**
     * @var string
     */
    private string $tableName = '';


    /**
     *
     */
    private function __construct()
    {
    }


    /**
     * @return SortFluent
     */
    static function init(): SortFluent
    {
        $sortFluent = new SortFluent();
        $sortFluent->sorts = collect();
        $sortFluent->originalSorts = collect();
        $sortFluent->customSorts = collect();
        $sortFluent->allowedSorts = collect();
        $sortFluent->setSortOptions(Context::request()->get());
        return $sortFluent;
    }


    /**
     * @param $query
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }


    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function setTable(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }


    /**
     * @param array $options
     *
     * @return $this
     */
    public function setSortOptions(array $options): SortFluent
    {
        if (isset($options['sort'])) {
            $this->sorts = collect($options['sort']);
            $this->originalSorts = collect($options['sort']);
        }
        return $this;
    }


    /**
     * @param array $sorts
     *
     * @return $this
     */
    public function setAllowedSorts(array $sorts): SortFluent
    {
        $this->allowedSorts = collect($sorts);
        return $this;
    }


    /**
     * @param $columnSort
     *
     * @return $this
     */
    public function removeSort($columnSort): SortFluent
    {
        $this->sorts = $this->sorts->reject(function ($value) use ($columnSort) {
            return is_array($columnSort) ?
                in_array($value['name'], $columnSort) :
                $value['name'] === $columnSort;
        });
        return $this;
    }


    /**
     * @param string  $sortColumn
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCustomSort(string $sortColumn, Closure $callback): SortFluent
    {
        $this->customSorts->push([$sortColumn, $callback]);
        return $this;
    }


    /**
     * @return $this
     * @throws ValidationException
     */
    public function validate(): SortFluent
    {
        $this->sorts->each(function ($sort){
            if (!isset($sort['name'])) {
                throw ValidationException::withMessages(['sort[column]' => __('core::validation.required', ['attribute' => 'sort[column]'])]);
            }
            if (!empty($this->allowedSorts) && !in_array($sort['name'], $this->allowedSorts->toArray())) {
                throw ValidationException::withMessages(['sort[column]' => __('core::validation.invalid', ['attribute' => 'sort[column]'])]);
            }
            if (isset($sort['direction']) && !($sort['direction'] instanceof SortTypes)) {
                throw ValidationException::withMessages(['sort[direction]' => __('core::validation.invalid', ['attribute' => 'sort[direction]'])]);
            }
        });
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
        if (!$this->allowedSorts->contains($column)) {
            throw ValidationException::withMessages(['sort[column]' => __('core::validation.invalid', ['attribute' => 'sort[column]'])]);
        }
    }


    /**
     * @return void
     * @throws ValidationException
     */
    public function applySort(): void
    {
        foreach ($this->sorts as $sort) {
            $this->validateColumn($sort['name']);
            self::applyBasicSort($this->query, $sort['name'], $sort['direction'], $this->tableName);
        }

        foreach ($this->customSorts as [$column, $callback]) {
            $sort = $this->getSort($column);
            if (!empty($sort)) {
                $this->validateColumn($sort['name']);
                $callback($this->query, $sort['name'], $sort['direction']);
            }
        }
    }


    /**
     * @param           $query
     * @param           $column
     * @param SortTypes $direction
     * @param string    $table
     *
     * @return void
     */
    public static function applyBasicSort($query, $column, SortTypes $direction, string $table = ''): void
    {
        $column = $table === '' ? $column : $table . "." . $column;
        $query->orderBy($column, $direction->value);
    }


    /**
     * @param        $query
     * @param        $column
     * @param        $direction
     * @param string $table
     *
     * @return void
     */
    public static function applyFileExtensionSort($query, $column, $direction, string $table = ''): void
    {
        $column = $table === '' ? $column : $table . "." . $column;
        $extensionColumn = DB::raw("SUBSTRING_INDEX($column, '.', -1)");
        $query->orderBy($extensionColumn, $direction);
    }


    /**
     * @param $sortColumn
     *
     * @return mixed|null
     */
    private function getSort($sortColumn): mixed
    {
        foreach ($this->originalSorts as $sort) {
            if ($sort['name'] === $sortColumn) {
                return $sort;
            }
        }
        return null;
    }
}
