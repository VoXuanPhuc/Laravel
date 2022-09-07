<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{

    protected $table = 'business_units';

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'id',
        'division_id',
        'organization_id',
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'organization_id',
        'division_id',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division() {
        return $this->belongsTo( Division::class );
    }
}
