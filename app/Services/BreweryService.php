<?php

namespace App\Services;

use App\Models\External\Brewery;
use App\Models\External\Meta;
use Illuminate\Support\Facades\Cache;
use App\Entities\BreweryResponse;

class BreweryService
{


    public function list(array $query): BreweryResponse|null
    {
        $cacheKey = 'brewery.list.' . urlencode(implode('_', array_keys($query ?? []))) . urlencode(implode('_', $query ?? []));
        if (Cache::has($cacheKey)) return Cache::get($cacheKey);
        $breweries = Brewery::get($query);
        if ($breweries->isEmpty()) return null;
        $meta = Meta::get('/breweries', $query);
        $breweriesResponse = new BreweryResponse($breweries, $meta);
        Cache::put($cacheKey, $breweriesResponse, now()->addMinute(10));
        return  $breweriesResponse;
    }
}
