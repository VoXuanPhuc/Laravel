<?php

namespace Encoda\Supplier\Models;

use Encoda\Core\Models\Model;
use Encoda\Core\Traits\HasUID;
use Encoda\Dependency\Enums\DependencyTypeEnum;
use Encoda\Dependency\Traits\DependencyModelTrait;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Supplier extends Model
{
    use HasUID, MultiTenancyModel, DependencyModelTrait;
    /**
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
        'uid',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'id',
        'pivot'
    ];

    protected  $casts = [
        'is_active' => 'boolean'
    ];

    protected $appends = [
        'type',
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(SupplierCategory::class, 'supplier_has_category');
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return HasMany
     */
    public function certs(): HasMany
    {
        return $this->hasMany(SupplierCert::class);
    }

}
