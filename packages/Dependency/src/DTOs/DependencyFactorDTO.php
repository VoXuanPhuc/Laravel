<?php

namespace Encoda\Dependency\DTOs;

use Illuminate\Database\Eloquent\Collection;

class DependencyFactorDTO extends BaseDTO
{

    public string $key;
    public string $name;
    public Collection $data;

    public function __construct( $key, $name, $data )
    {
        $this->key = $key;
        $this->name = $name;
        $this->data = $data;
    }
}
