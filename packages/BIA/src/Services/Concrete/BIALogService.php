<?php

namespace Encoda\BIA\Services\Concrete;

use Encoda\BIA\Models\BIA;
use Encoda\BIA\Repositories\Interfaces\BIARepositoryInterface;
use Encoda\BIA\Services\Interfaces\BIALogServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BIALogService implements BIALogServiceInterface
{

    public function __construct( protected BIARepositoryInterface $biaRepository )
    {
    }

    /**
     * @param string $uid
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    public function getLogs(string $uid)
    {
        /** @var BIA $bia */
       $bia = $this->biaRepository->findByUid( $uid );

       if( !$bia ) {
           throw new NotFoundException( 'BIA not found' );

       }

       return $bia->logs()->paginate();
    }
}
