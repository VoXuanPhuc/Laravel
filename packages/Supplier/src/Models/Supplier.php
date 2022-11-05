<?php

namespace Encoda\Supplier\Models;

use Encoda\Core\Models\Model;
use Encoda\Core\Traits\HasUID;
use Encoda\Dependency\Traits\DependencyModelTrait;
use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Encoda\EDocs\Traits\InteractsWithDocument;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Notification\Traits\NotifySender;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Encoda\Notification\Enums\EventNotificationRuleActionEnum;

/**
 *
 */
class Supplier extends Model
{
    use HasUID, MultiTenancyModel, DependencyModelTrait, InteractsWithDocument;
    use EasyActionLogTrait;
    use NotifySender;
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
        'tag_color',
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
     * @return Collection
     */
    public function getCertsAttribute()
    {
        return $this->getDocuments('certs');
    }

}
