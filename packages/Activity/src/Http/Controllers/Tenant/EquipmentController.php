<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\EquipmentServiceInterface;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{

    public function __construct( protected EquipmentServiceInterface $equipmentService )
    {
    }

    /**
     * @return mixed
     */
    public function index() {
        return $this->equipmentService->listEquipments( $this->getTenant()->uid );
    }

    public function create( Request $request ) {

    }

    public function update( Request $request, $uid ) {

    }

    public function delete( $uid ) {

    }
}
