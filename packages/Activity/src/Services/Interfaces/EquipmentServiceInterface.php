<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface EquipmentServiceInterface
{

    public function listEquipments();

    public function getEquipment( $uid );

    public function createEquipment( Request $request );

    public function updateEquipment( Request $request, $uid );

    public function deleteEquipment( $uid );
}
