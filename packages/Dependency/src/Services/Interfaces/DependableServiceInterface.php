<?php

namespace Encoda\Dependency\Services\Interfaces;

interface DependableServiceInterface
{

    public function getDependableByTypeAndUids($type, $uids );

    public function getDependableByTypeAndUid( $type, $uid );
}
