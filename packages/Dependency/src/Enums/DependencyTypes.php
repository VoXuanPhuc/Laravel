<?php

namespace Encoda\Dependency\Enums;

enum DependencyTypes: string
{
    case UPSTREAM = "Upstream";

    case DOWNSTREAM = "Downstream";
}
