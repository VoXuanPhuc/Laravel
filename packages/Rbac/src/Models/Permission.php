<?php

namespace Encoda\Rbac\Models;

class Permission extends \Spatie\Permission\Models\Permission
{

    public function group() {
        return $this->belongsTo( PermissionGroup::class );
    }
}
