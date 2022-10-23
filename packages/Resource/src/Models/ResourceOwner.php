<?php

namespace Encoda\Resource\Models;

use Encoda\Core\Models\Model;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ResourceOwner extends Model
{

    use MultiTenancyModel;
    use EasyActionLogTrait;

    protected $table = 'resource_owners';


    protected $guarded = [
        'id',
        'uid',

    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'is_invite',
        'email',
        'organization_id',
    ];

    protected $hidden = [
        'id',
        'organization_id',
    ];

    protected $casts  = [
        'is_invite' => 'boolean'
    ];

    protected $appends = [
        'full_name' => 'full_name',
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute() {
        $names = [ $this->first_name, $this->last_name];

        return implode( ' ', $names );
    }

    /**
     * @return HasManyThrough
     */
    public function resources(): HasManyThrough
    {
        return $this->hasManyThrough( Resource::class, 'owners_has_resources' );
    }
}
