<?php

namespace App\Enums;

enum BrewerySort: string
{
    case CITY = 'city';
    case COUNTRY = 'country';
    case IDS = 'ids';
    case NAME = 'name';
    case STATE = 'state';
    case TYPE = 'type';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
