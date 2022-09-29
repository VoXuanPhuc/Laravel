<?php

namespace Encoda\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Resource extends Model
{
    protected $table = 'resources';

    protected $guarded = [
        'id',
        'uid',

    ];

    protected $fillable = [
        'name',
        'description',
        'status',
        'organization_id',
        'resources_category_id',
    ];

    protected $hidden = [
        'id',
    ];

    /**
     * @return BelongsToMany
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany( ResourceOwner::class, 'owners_has_resources' );
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo( ResourceCategory::class, 'resources_category_id' );
    }
}
