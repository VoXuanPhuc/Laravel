<?php

namespace Encoda\Supplier\Models;

use Encoda\Core\Traits\HasUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCert extends Model
{
    use HasUID;

    /**
     * @var string
     */
    protected $table = 'supplier_certs';

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
        'supplier_id'
    ];

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

}
