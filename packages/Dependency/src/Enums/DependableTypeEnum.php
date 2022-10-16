<?php

namespace Encoda\Dependency\Enums;

enum DependableTypeEnum: string
{
    case TARGET = "Target";

    case UPSTREAM = "Upstream";

    case DOWNSTREAM = "Downstream";
}
