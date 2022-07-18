<?php
namespace Encoda\Auth\Http\Resources;

use Encoda\Core\Http\Resources\BaseJsonResource;

class RoleResource extends BaseJsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];


    }
}
