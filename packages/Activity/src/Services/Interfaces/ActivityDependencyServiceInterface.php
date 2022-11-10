<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\SaveDependenciesAndSuppliersRequest;

interface ActivityDependencyServiceInterface {

    public function saveDependenciesAndSuppliers(SaveDependenciesAndSuppliersRequest $request, $activityUid);

}
