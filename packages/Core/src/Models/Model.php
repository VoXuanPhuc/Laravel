<?php

namespace Encoda\Core\Models;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Get table name
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
