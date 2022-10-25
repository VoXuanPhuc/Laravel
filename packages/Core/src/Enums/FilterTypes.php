<?php


namespace Encoda\Core\Enums;


enum FilterTypes: string
{
    case IS = 'IS';
    case IS_NOT = 'IS_NOT';
    case CONTAIN = 'CONTAIN';
    case CUSTOM = 'CUSTOM';
    case LESS_THAN = 'LT';
    case GREATER_THAN = 'GT';
    case LESS_THAN_OR_EQUAL = 'LTE';
    case GREATER_THAN_OR_EQUAL = 'GTE';
    case IN = 'IN';
    case BETWEEN = 'BETWEEN';
    case BOOLEAN = 'BOOLEAN';

}
