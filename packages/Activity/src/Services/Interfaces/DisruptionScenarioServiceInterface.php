<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface DisruptionScenarioServiceInterface
{
    public function listDisruptionScenarios();

    public function getDisruptionScenario( $uid );

    public function createDisruptionScenario( Request $request );

    public function updateDisruptionScenario(Request $request, $uid);

    public function deleteDisruptionScenario( $uid );
}
