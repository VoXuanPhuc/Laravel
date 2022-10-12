<?php

namespace Encoda\Supplier\Models;

use Encoda\Core\Models\Model;
use Encoda\Core\Traits\HasUID;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupplierCategory extends Model
{
    use HasUID, MultiTenancyModel;
    /**
     * @var string
     */
    protected $table = 'supplier_categories';

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

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return BelongsToMany
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_has_category');
    }


}
