<?php

namespace Encoda\Core\Enums;

enum SystemFeatureEnum : string
{

    case DASHBOARD = 'dashboard'; // For landlord
    case BRIGHT_DASHBOARD = 'bright_dashboard'; // For clients
    case NOTIFICATION ='notification';
    case ORGANIZATION ='organization';
    case DEPARTMENT ='department';
    case ACTIVITY ='activity';
    case RESOURCE ='resource';
    case SUPPLIER ='supplier';
    case DEPENDENCY ='dependency';
    case ASSESSMENT ='assessment';
    case BCP ='business_continuity_plan';
    case INDUSTRY ='industry';
    case USER ='user';
    case REPORT ='report';
    case SETTING ='setting';
}
