<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $table = 'divisions';

    protected $guarded = [
        'id',

    ];

    protected $fillable = [
        'name',
        'description',
        'avatar_color',
        'organization_id',
        'is_active',
    ];

    protected $hidden  = [
        'id',
        'organization_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    /**
     * @return HasMany
     */
    public function business_units() {
        return $this->hasMany( BusinessUnit::class );
    }
}
