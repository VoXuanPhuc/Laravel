<?php

namespace Encoda\BCP\Services\Interfaces;

use Encoda\BCP\Http\Requests\Report\CreateBCPRequest;
use Encoda\BCP\Http\Requests\Report\UpdateBCPRequest;

interface BCPServiceInterface
{
    public function list();
    public function getBCP(string $uid);
    public function create(CreateBCPRequest $request);
    public function update(UpdateBCPRequest $request, string $uid);
    public function delete(string $uid);
}
