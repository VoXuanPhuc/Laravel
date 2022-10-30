<?php

namespace Encoda\BCP\Services\Concrete;

use Encoda\BCP\Models\BCP;
use Encoda\BCP\Repositories\Interfaces\BCPRepositoryInterface;
use Encoda\BCP\Services\Interfaces\BCPLogServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BCPLogService implements BCPLogServiceInterface
{

    public function __construct( protected BCPRepositoryInterface $bcpRepository )
    {
    }

    /**
     * @param string $uid
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    public function getLogs(string $uid)
    {
        /** @var BCP $bcp */
       $bcp = $this->bcpRepository->findByUid( $uid );

       if( !$bcp ) {
           throw new NotFoundException( 'BCP not found' );

       }

       return $bcp->logs()->paginate();
    }
}
