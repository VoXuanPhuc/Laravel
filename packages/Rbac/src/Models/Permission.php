<?php

namespace Encoda\Rbac\Models;

use Encoda\Rbac\Contract\PermissionContract;

class Permission extends \Spatie\Permission\Models\Permission implements PermissionContract
{

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'uid',
        'name',
        'label',
        'guard_name',
        'group_id',
    ];

    public function group() {
        return $this->belongsTo( PermissionGroup::class );
    }
}
