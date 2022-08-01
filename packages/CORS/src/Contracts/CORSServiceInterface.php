<?php

namespace Encoda\CORS\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface CORSServiceInterface
{

    /**
     * Handles a preflight request.
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function handlePreflightRequest(Request $request);


    /**
     * Handles the actual request.
     *
     * @param Request  $request
     * @param $response
     *
     * @return Response
     */
    public function handleRequest(Request $request, $response);


    /**
     * Returns whether or not the request is a CORS request.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isCORSRequest(Request $request);


    /**
     * Returns whether or not the request is a preflight request.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isPreflightRequest(Request $request);
}
