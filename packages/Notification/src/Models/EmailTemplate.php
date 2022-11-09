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
 *
 */
class EmailTemplate extends Model
{
    use SoftDeletes;
    use InteractsWithDocument;
    use MultiTenancyModel;
    use HasOwner;

    /**
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * @var string[]
     */
    protected $guarded = ['id', 'uid', 'deleted_at', 'system_mail'];


    /**
     * @var string[]
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'documents'
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'attachments'
    ];

    protected $casts = [
        'system_mail' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventNotifications()
    {
        return $this->hasMany(EventNotification::class);
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

}
