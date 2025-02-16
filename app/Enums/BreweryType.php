<?php

namespace App\Enums;

enum BreweryType: string
{
    case MICRO = 'micro';
    case NANO = 'inactive';
    case REGIONAL = 'regional';
    case BREWPUB = 'brewpub';
    case LARGE = 'large';
    case PLANNING = 'planning';
    case BAR = 'bar';
    case CONTRACT = 'contract';
    case PROPRIETOR = 'proprietor';
    case CLOSED = 'closed';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
