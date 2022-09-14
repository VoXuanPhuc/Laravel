<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ITSolutionContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ITSolution extends Model implements ITSolutionContract
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'it_solutions';
    
    protected $fillable = [
        'location',
        'data_type',
        'activity_id',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'activity_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @return BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

}
