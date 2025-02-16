<?php

namespace App\Entities;

use Illuminate\Support\Collection;
use App\Models\External\Meta;

class BreweryResponse
{
    public array $breweries;
    public array $meta;

    public function __construct(Collection $breweries, Meta $meta)
    {
        $this->breweries = array_map(fn($brewery) =>  $brewery->toArray(), $breweries->toArray());
        $this->meta = $meta->toArray();
    }
}
