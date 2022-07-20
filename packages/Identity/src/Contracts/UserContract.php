<?php

namespace Encoda\Identity\Contracts;

interface UserContract
{

    public function create( $attributes );
    public function find( $id );
    public function list( $columns = ['*']);
}
