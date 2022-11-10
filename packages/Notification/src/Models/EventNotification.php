<?php

namespace Encoda\Notification\Models;

use Encoda\Core\Models\Model;
use Encoda\EDocs\Traits\InteractsWithDocument;
use Encoda\Identity\Models\Database\User;
use Encoda\Identity\Traits\HasOwner;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Notification\Enums\EventNotificationStatusEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property id
 */
class EventNotification extends Model
{
    use SoftDeletes;
    use InteractsWithDocument;
    use MultiTenancyModel;
    use HasOwner;

    /**
     * @var string
     */
    protected $table = 'event_notifications';

    /**
     * @var string[]
     */
    protected $guarded = ['id', 'uid', 'status', 'deleted_at'];

    /**
     * @var string[]
     */
    protected $dates = [
        'dispatch_after'
    ];

    // automatically handles json_encode, json_decode to php array
    /**
     * @var string[]
     */
    protected $casts = [
        'pinned' => 'boolean',
        'all_user' => 'boolean',
        'methods' => 'array',
        'status'  => EventNotificationStatusEnum::class,
        'type'    => EventNotificationTypeEnum::class,
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'documents',
        'created_by'
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'attachments'
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'emailTemplate'
    ];

    /**
     * @return BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules()
    {
        return $this->hasMany(EventNotificationRule::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'receivable', EventNotificationReceiver::getTableName());
    }

    /**
     * @return Collection
     */
    public function getAttachmentsAttribute()
    {
        return $this->getDocuments('attachments');
    }

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @param $query
     * @param $isAcitve
     *
     * @return void
     */
    public function scopeActive($query, $isAcitve = true)
    {
        $query->where('is_active', $isAcitve);
    }

    /**
     * @return BelongsTo
     */
    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

}
