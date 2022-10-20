<?php

namespace Encoda\AWSCognito\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class CognitoBaseModel implements AuthenticatableContract,AuthorizableContract, Jsonable
{

    use Authenticatable, Authorizable, HasFactory;

    protected string $primaryKey = 'id';

    protected array $ignores = [];

    public function __construct($attributes = [] )
     {
        $this->fill( $attributes );
    }

    /**
     * @param $attributes
     */
    protected function fill( $attributes ) {
         foreach ( $attributes as $key => $value ) {
             $this->{$key} = $value;
         }
     }


    /**
     * @param int $options
     * @return string
     */
     public function toJson($options = 0)
     {
         /** @var Serializer $serializer */
         $serializer = app(Serializer::class);

         return $serializer->serialize( $this, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES  => $this->ignores] );
     }


    /**
     * @return string
     */
     protected function getKeyName() {
         return $this->primaryKey;
     }
 }
