<?php
namespace Encoda\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends BaseJsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return string[]
     */
    public function toArray( $request )
    {
        return [
            'id' => 't',
            'username' => 'nam.nguyens',
            'pass' => 'Pass',
            'message' => 'tme',
        ];
    }

}
