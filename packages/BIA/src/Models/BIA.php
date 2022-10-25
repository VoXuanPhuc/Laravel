<?php

namespace Encoda\BIA\Models;

use Encoda\Core\Models\Model;
use Encoda\Core\Traits\HasUID;
use Encoda\EDocs\Traits\InteractsWithDocument;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class BIA extends Model
{
    use HasUID, MultiTenancyModel, InteractsWithDocument, SoftDeletes;

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

    /**
     * @return Collection
     */
    public function getReportsAttribute()
    {
        return $this->getDocuments('reports');
    }
}
