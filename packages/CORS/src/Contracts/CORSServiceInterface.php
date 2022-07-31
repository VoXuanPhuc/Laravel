<?php

namespace Encoda\CORS\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface CORSServiceInterface
{

    /**
     * Handles a preflight request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handlePreflightRequest(Request $request): Response;


    /**
     * Handles the actual request.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function handleRequest(Request $request, Response $response): Response;


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
