<?php
namespace Encoda\Core\Http\HttpStatus;


interface HttpStatusCode
{
    const __default = self::OK;

    const SWITCHING_PROTOCOLS = 101;

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NON_AUTHORITATIVE_INFORMATION = 203;
    const NO_CONTENT = 204;
    const RESET_CONTENT = 205;
    const PARTIAL_CONTENT = 206;

    const MULTIPLE_CHOICES = 300;
    const MOVED_PERMANENTLY = 301;
    const MOVED_TEMPORARILY = 302;
    const SEE_OTHER = 303;
    const NOT_MODIFIED = 304;
    const USE_PROXY = 305;
    const PERMANENT_REDIRECT = 308;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    const REQUEST_TIMEOUT = 408;
    const CONFLICT = 409;
    const GONE = 410;
    const LENGTH_REQUIRED = 411;
    const PRECONDITION_FAILED = 412;
    const REQUEST_ENTITY_TOO_LARGE = 413;
    const REQUEST_URI_TOO_LARGE = 414;
    const UNSUPPORTED_MEDIA_TYPE = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const EXPECTATION_FAILED = 417;
    const IM_A_TEAPOT = 418;
    const UNPROCESSABLE_ENTITY = 422;

    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    const STATUS_CODES = [
        self::SWITCHING_PROTOCOLS,
        self::OK,
        self::CREATED,
        self::ACCEPTED,
        self::NON_AUTHORITATIVE_INFORMATION,
        self::NO_CONTENT,
        self::RESET_CONTENT,
        self::PARTIAL_CONTENT,
        self::MULTIPLE_CHOICES,
        self::MOVED_PERMANENTLY,
        self::MOVED_TEMPORARILY,
        self::SEE_OTHER,
        self::NOT_MODIFIED,
        self::USE_PROXY,
        self::PERMANENT_REDIRECT,
        self::BAD_REQUEST,
        self::UNAUTHORIZED,
        self::PAYMENT_REQUIRED,
        self::FORBIDDEN,
        self::NOT_FOUND,
        self::METHOD_NOT_ALLOWED,
        self::NOT_ACCEPTABLE,
        self::PROXY_AUTHENTICATION_REQUIRED,
        self::REQUEST_TIMEOUT,
        self::CONFLICT,
        self::GONE,
        self::LENGTH_REQUIRED,
        self::PRECONDITION_FAILED,
        self::REQUEST_ENTITY_TOO_LARGE,
        self::REQUEST_URI_TOO_LARGE,
        self::UNSUPPORTED_MEDIA_TYPE,
        self::REQUESTED_RANGE_NOT_SATISFIABLE,
        self::EXPECTATION_FAILED,
        self::IM_A_TEAPOT,
        self::INTERNAL_SERVER_ERROR,
        self::NOT_IMPLEMENTED,
        self::BAD_GATEWAY,
        self::SERVICE_UNAVAILABLE,
        self::GATEWAY_TIMEOUT,
        self::HTTP_VERSION_NOT_SUPPORTED
    ];
}
