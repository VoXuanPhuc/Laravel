<?php
namespace Encoda\Rbac\Models;

use Encoda\Rbac\Contract\RoleContract;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property Collection $permissions
 */
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


    /**
     * @return Collection
     */
    public function getpermissions() {
        return $this->permissions;
    }
}
