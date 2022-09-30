<?php

namespace Encoda\Resource\Enums;

enum ResourceStatusEnum : int
{
    case FREE = 1;
    case ALLOCATED = 2;
    case DESTROYED = 3;

}
