<?php
namespace Encoda\Rbac\Models;

use Encoda\Rbac\Contract\RoleContract;

class Role extends \Spatie\Permission\Models\Role implements RoleContract
{

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'uid',
        'tenant_id',
        'name',
        'description',
        'guard_name',
    ];

    public static $snakeAttributes = false;
}
