<?php

namespace Encoda\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceCategory extends Model
{

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
