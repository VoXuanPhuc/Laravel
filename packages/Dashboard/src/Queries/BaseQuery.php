<?php

namespace Encoda\Dashboard\Queries;

use Carbon\Carbon;
use DateTimeInterface;
use DateTimeZone;
use Encoda\Organization\Models\Organization;
use Illuminate\Contracts\Database\Query\Builder;

abstract class BaseQuery
{

    protected Organization $tenant;

    protected Builder $query;

    protected DateTimeInterface $from;

    protected DateTimeInterface $to;

    public function __construct( DateTimeInterface $from = null, DateTimeInterface $to = null )
    {
        $this->tenant = tenant();
        $this->from = $from ?? Carbon::today();
        $this->to = $to ?? Carbon::now();
    }

    public abstract function buildQuery();
    public abstract function transform();
}
