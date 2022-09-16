<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface EquipmentServiceInterface
{

    public function listEquipments($organizationUid );

    public function getEquipment($organizationUid, $uid);

    public function createEquipment(Request $request, $organizationUid );

    public function updateEquipment(Request $request, $organizationUid, $uid);

    public function deleteEquipment($organizationUid, $uid);
}
