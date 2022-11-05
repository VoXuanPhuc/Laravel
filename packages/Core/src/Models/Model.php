<?php

namespace Encoda\Core\Models;
use Encoda\Identity\Models\Database\User;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Schema;

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

    public static function getModelAllowedAttribute()
    {
        $attrs = collect(Schema::getColumnListing(self::getTableName()));
        $attrs = $attrs->reject(function ($item){
            return in_array($item, with(new static)->hidden);
        });
        return array_values($attrs->toArray());
    }
}
