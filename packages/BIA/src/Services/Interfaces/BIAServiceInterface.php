<?php

namespace Encoda\BIA\Services\Interfaces;

use Encoda\BIA\Http\Requests\Report\CreateBIARequest;
use Encoda\BIA\Http\Requests\Report\UpdateBIARequest;

interface BIAServiceInterface
{
    public function list();
    public function getBIA(string $uid);
    public function create(CreateBIARequest $request);
    public function update(UpdateBIARequest $request, string $uid);
    public function delete(string $uid);
}
