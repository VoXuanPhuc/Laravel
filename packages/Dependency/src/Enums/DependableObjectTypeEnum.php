<?php

namespace Encoda\Dependency\Enums;

enum DependableObjectTypeEnum: string
{
    case RESOURCE = 'Resource';
    case ACTIVITY   = 'Activity';
    case SUPPLIER   = 'Supplier';
}
