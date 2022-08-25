<?php

namespace Encoda\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroup extends Model
{
    protected $table = 'permission_groups';

    /**
     * @return HasMany
     */
    public function permissions() {
        return $this->hasMany(Permission::class, 'group_id');
    }

}
