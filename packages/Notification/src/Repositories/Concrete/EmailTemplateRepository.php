<?php

namespace Encoda\Notification\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Notification\Models\EmailTemplate;
use Encoda\Notification\Repositories\Interfaces\EmailTemplateRepositoryInterface;

class EmailTemplateRepository extends Repository implements EmailTemplateRepositoryInterface
{

    public function model()
    {
        return EmailTemplate::class;
    }
}
