<?php

namespace Encoda\AWSCognito\Models;

use Illuminate\Contracts\Support\Jsonable;
use Symfony\Component\Serializer\Serializer;

class CognitoBaseModel implements  Jsonable
{

     public function __construct( $attributes = [] )
     {
        $this->fill( $attributes );
     }

     protected function fill( $attributes ) {
         foreach ( $attributes as $key => $value ) {
             $this->{$key} = $value;
         }
     }


     public function toJson($options = 0)
     {
         /** @var Serializer $serializer */
         $serializer = app(Serializer::class);

         return $serializer->serialize( $this, 'json' );
     }


 }
