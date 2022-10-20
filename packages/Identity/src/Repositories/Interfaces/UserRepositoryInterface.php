<?php

namespace Encoda\Identity\Repositories\Interfaces;


interface UserRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * Find by Email
     * @param $email
     * @param string[] $column
     * @return mixed
     */
    public function findByEmail($email, array $column = ['*'] ): mixed;
}
