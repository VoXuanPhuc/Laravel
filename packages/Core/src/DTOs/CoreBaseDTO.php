<?php

namespace Encoda\Core\DTOs;

use Illuminate\Contracts\Support\Jsonable;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoreBaseDTO implements Jsonable
{

    protected array $ignores = [];

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        /** @var Serializer $serializer */
        $serializer = app( 'core.serializer' );

        return $serializer->serialize( $this, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES  => $this->ignores] );
    }
}
