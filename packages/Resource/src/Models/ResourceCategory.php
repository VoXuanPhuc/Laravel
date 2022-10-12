<?php

namespace Encoda\Resource\Models;

use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceCategory extends Model
{

    use MultiTenancyModel;

    protected $table = 'resource_categories';

    protected $guarded = [
        'id',
        'uid',

    ];

    protected $fillable = [
        'name',
        'description',
        'organization_id',
    ];

    protected $hidden = [
        'id',
        'organization_id',
    ];

    /**
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany( Resource::class );
    }
}
