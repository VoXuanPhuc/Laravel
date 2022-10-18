<?php

namespace Encoda\EDocs\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\EDocs\Models\Document;
use Encoda\EDocs\Repositories\Interfaces\DocumentRepositoryInterface;

/**
 *
 */
class DocumentRepository extends Repository implements DocumentRepositoryInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return Document::class;
    }
}
