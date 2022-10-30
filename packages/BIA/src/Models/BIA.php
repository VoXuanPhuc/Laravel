<?php

namespace Encoda\BIA\Models;

use Encoda\BIA\Enums\BIAStatusEnum;
use Encoda\Core\Models\Model;
use Encoda\Core\Traits\HasUID;
use Encoda\Core\Traits\StatusEnumModel;
use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogModel;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Encoda\EDocs\Traits\InteractsWithDocument;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $name
 * @property $uid
 * @property $due_date
 * @property $logs
 */
class BIA extends Model
{
    use HasUID, MultiTenancyModel, InteractsWithDocument, SoftDeletes;
    use EasyActionLogTrait, EasyActionLogModel;

    /**
     * @var string
     */
    protected $table = 'bia';

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
        'organization_id'
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'due_date'
    ];

    protected $appends = [
        'report_count'
    ];

    protected $casts = [
        'status' => BIAStatusEnum::class,
    ];

    protected $attributeNameMaps = [
        'due_date' => 'Due Date',
        'name' => 'Assessment name',
        'status' => 'Status',
    ];

    /**
     * @return Collection
     */
    public function getReportsAttribute()
    {
        return $this->getDocuments('reports');
    }
    /**
     * @return int
     */
    public function getReportCountAttribute()
    {
        return $this->getDocuments('reports')->count();
    }
}
