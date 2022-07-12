<?php

namespace Encoda\Core\Http\Header;

interface Header
{

    public const AIM = 'A-IM';
    public const ACCEPT = 'Accept';
    public const ACCEPT_CHARSET = 'Accept-Charset';
    public const ACCEPT_DATETIME = 'Accept-Datetime';
    public const ACCEPT_ENCODING = 'Accept-Encoding';
    public const ACCEPT_LANGUAGE = 'Accept-Language';
    public const AUTHORIZATION = 'Authorization';
    public const CACHE_CONTROL = 'Cache-Control';
    public const CONTENT_ENCODING = 'Content-Encoding';
    public const CONTENT_LENGTH = 'Content-Length';
    public const CONTENT_MD5 = 'Content-MD5';
    public const CONTENT_TYPE = 'Content-Type';
    public const COOKIE = 'Cookie';
    public const HOST = 'Host';
    public const IF_MATCH = 'If-Match';
    public const USERAGENT = 'User-Agent';
}
