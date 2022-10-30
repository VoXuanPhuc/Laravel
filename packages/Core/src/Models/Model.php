<?php

namespace Encoda\Core\Models;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{

    protected $attributeNameMaps = [];

    /**
     * Get table name
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    /**
     * @param $attr
     * @return mixed
     */
    public function getAttributeName( $attr ): mixed
    {
        return $this->attributeNameMaps[ $attr ] ?? $attr;

    }

}
