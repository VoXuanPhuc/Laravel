<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\RoleContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends \Encoda\Rbac\Models\Role implements RoleContract
{
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
    
}
