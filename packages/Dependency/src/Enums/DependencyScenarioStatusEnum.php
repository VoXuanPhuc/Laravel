<?php

namespace Encoda\Dependency\Enums;

enum DependencyScenarioStatusEnum : int
{

    case CREATED = 1;
    case IN_PROGRESS = 2;
    case IN_REVIEW = 3;
    case FINISHED = 4;
}
