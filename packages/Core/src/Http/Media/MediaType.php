<?php

namespace Encoda\Core\Http\Media;

interface MediaType
{

    const APPLICATION_ATOM_XML = 'application/atom+xml';
    const APPLICATION_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const APPLICATION_JSON = 'application/json';
    const APPLICATION_JSON_ODATA = 'application/json; odata.metadata=minimal; odata.streaming=true';
    const APPLICATION_OCTET_STREAM = 'application/octet-stream';
    const APPLICATION_SVG_XML = 'application/svg+xml';
    const APPLICATION_XHTML_XML = 'application/xhtml+xml';
    const APPLICATION_XML = 'application/xml';
    const CHARSET_PARAMETER = 'charset';
    const MEDIA_TYPE_WILDCARD = '*';
    const MULTIPART_FORM_DATA = 'multipart/form-data';
    const TEXT_HTML = 'text/html';
    const TEXT_PLAIN = 'text/plain';
    const TEXT_XML = 'text/xml';
    const JSON = 'json';
    const WILDCARD = '*/*';
}
