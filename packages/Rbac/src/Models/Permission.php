<?php

namespace Encoda\Rbac\Models;

use Encoda\Rbac\Contract\PermissionContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends \Spatie\Permission\Models\Permission implements PermissionContract
{

    protected $fillable = [
        'name',
        'label',
        'group_id',
        'guard_name',
    ];

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo( PermissionGroup::class );
    }
}
