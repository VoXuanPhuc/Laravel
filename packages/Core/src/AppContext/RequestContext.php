<?php

namespace Encoda\Core\AppContext;

use Encoda\Core\Enums\FilterTypes;
use Encoda\Core\Enums\SortTypes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 *
 */
class RequestContext extends ContextBase
{
    /**
     * @var array
     */
    private array $options = array();

    public function __construct(Request $request)
    {
        $this->fromRequest($request);
    }

    /**
     * @throws ValidationException
     */
    public function fromRequest(Request $request): RequestContext
    {
        if ($request->has('page')) {
            $this->setPageOptions($request->get('page'));
        }

        if ($request->has('sort')) {
            $this->setSortOptions($request->get('sort'));
        }

        if ($request->has('filter')) {
            $this->setFilterOptions($request->get('filter'));
        }
        return $this;
    }

    /**
     * @return array[]
     */
    public function get(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param $page
     */
    private function setPageOptions($page): void
    {
        $this->options['page']['size'] = (int)($page['size'] ?? config('config.pagination_size'));
        $this->options['page']['number'] = (int)($page['number'] ?? 1);
    }

    /**
     * @param $sort
     *
     * @return void
     * @throws ValidationException
     */
    private function setSortOptions($sort): void
    {
        //Check is request single sort?
        $sortArray = [];
        if (is_array($sort) && isset($sort['name'])) {
            $sortArray[] = $sort;
        } else {
            $sortArray = $sort;
        }
        foreach ($sortArray as &$sortItem) {
            if (!isset($sortItem['name'])) {
                throw ValidationException::withMessages(['sort[name]' => __('core::validation.required', ['attribute' => 'sort[name]'])]);
            }
            if(isset($sortItem['direction'])){
                $sortItem['direction'] = SortTypes::tryFrom(strtoupper(trim($sortItem['direction'])));
                if (!$sortItem['direction']) {
                    throw ValidationException::withMessages(['sort[direction]' => __('core::validation.invalid', ['attribute' => 'sort[direction]'])]);
                }
            }else{
                $sortItem['direction'] = SortTypes::ASC;
            }


        }
        $this->options['sort'] = $sortArray;
    }

    /**
     * @param $filters
     *
     * @return void
     * @throws ValidationException
     */
    private function setFilterOptions($filters): void
    {
        foreach ($filters as &$filter) {
            if (!isset($filter['value'])) {
                throw ValidationException::withMessages(['filter[value]' => __('core::validation.required', ['attribute' => 'filter[value]'])]);
            }
            if (!isset($filter['name'])) {
                throw ValidationException::withMessages(['filter[name]' => __('core::validation.required', ['attribute' => 'filter[name]'])]);
            }
            if (isset($filter['type'])) {
                $filter['type'] = FilterTypes::tryFrom(strtoupper(trim($filter['type'])));
                if (!$filter['type']) {
                    throw ValidationException::withMessages(['filter[type]' => __('core::validation.invalid', ['attribute' => 'filter[type]'])]);
                }
            } else {
                $filter['type'] = FilterTypes::IS;
            }
        }
        $this->options['filter'] = $filters;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return !empty($this->options);
    }
}
