<?php

namespace Encoda\Activity\Services\Concrete;

class BaseActivityService
{

    /**
     * @return string[]
     */
    protected function relationsQuery(): array
    {
        $relations = request('relations' );

        return empty($relations) ? [] : explode('&', request('relations' ) );
    }
}
