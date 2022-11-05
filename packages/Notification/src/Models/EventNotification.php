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

    protected $table = 'event_notifications';

    protected $guarded = ['id', 'uid', 'status', 'deleted_at'];

    /**
     * @var string[]
     */
    protected $dates = [
        'dispatch_after'
    ];

    // automatically handles json_encode, json_decode to php array
    protected $casts = [
        'methods' => 'array',
        'status'  => EventNotificationStatusEnum::class,
        'type'    => EventNotificationTypeEnum::class,
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'documents'
    ];

    protected $appends = [
        'attachments'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function rules()
    {
        return $this->hasMany(EventNotificationRule::class);
    }


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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query, $isAcitve = true)
    {
        $query->where('is_active', $isAcitve);
    }

}
