<?php

namespace Encoda\Core\Http\Responses;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Encoda\Core\Http\Resources\BaseJsonResource;
use Encoda\Core\Interfaces\ResponseInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Lumen\Http\ResponseFactory;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class BaseResponse extends \Illuminate\Http\Response implements ResponseInterface
{

    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $content = $this->buildSuccess( $content );
        parent::__construct($content, $status, $headers);
    }

    /**
     * @param $content
     * @return array
     */
    protected function buildSuccess( $content ) {

        if( $content instanceof  BaseJsonResource || $content instanceof AnonymousResourceCollection) {
            return [
                'success' => true,
                'data' => $content
            ];


        }

        return $content;
    }
}
