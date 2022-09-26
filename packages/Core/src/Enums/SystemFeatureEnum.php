<?php

namespace Encoda\Core\Enums;

enum SystemFeatureEnum : string
{

    case DASHBOARD = 'dashboard';
    case NOTIFICATION ='notification';
    case ORGANIZATION ='organization';
    case DEPARTMENT ='department';
    case ACTIVITY ='activity';
    case RESOURCE ='resource';
    case SUPPLIER ='supplier';
    case INDUSTRY ='industry';
    case USER ='user';
    case REPORT ='report';
    case SETTING ='setting';
}
