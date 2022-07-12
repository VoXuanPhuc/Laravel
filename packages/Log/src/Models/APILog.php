<?php

namespace Encoda\Log\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class APILog extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_logs';

    /**
     * @var string[]
     */
    protected $guarded = ['id'];
}
