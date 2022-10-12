<?php

namespace Encoda\Resource\Models;

use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Resource\Enums\ResourceStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{

    use SoftDeletes, MultiTenancyModel;
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


    public function getStatus() {
        return ResourceStatusEnum::from( $this->status );
    }

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
