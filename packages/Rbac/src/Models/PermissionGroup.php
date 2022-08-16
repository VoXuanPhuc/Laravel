<?php

namespace Encoda\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions() {
        return $this->hasMany(Permission::class, 'group_id');
    }

}
