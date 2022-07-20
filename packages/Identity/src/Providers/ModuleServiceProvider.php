<?php

namespace Encoda\Identity\Providers;

use Encoda\Core\Providers\CoreModuleServiceProvider;
use Encoda\Identity\Models\Cognito\CognitoUser;

class ModuleServiceProvider extends CoreModuleServiceProvider
{

    protected $models = [
        CognitoUser::class,
    ];
}
