<?php
namespace Encoda\Rbac\Models;

use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Rbac\Contract\RoleContract;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property Collection $permissions
 */
class Role extends \Spatie\Permission\Models\Role implements RoleContract
{

    use MultiTenancyModel;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'uid',
        'organization_id',
        'name',
        'label',
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
