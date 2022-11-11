<?php

namespace Encoda\EasyLog\Enums;

enum LogEventEnum : string
{

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}
