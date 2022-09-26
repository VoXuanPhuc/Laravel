<?php

namespace Encoda\Core\DTOs;

use Illuminate\Contracts\Support\Jsonable;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoreBaseDTO implements Jsonable
{

    protected $ignores = [];

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
}
