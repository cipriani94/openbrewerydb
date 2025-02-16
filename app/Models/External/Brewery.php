<?php

namespace App\Models\External;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class Brewery extends AbstractExternalModel
{

    public static function get(array $query = []): Collection
    {
        $response = Http::openbrewerydb()->get('/breweries', $query);
        if ($response->failed()) return collect([]);
        return collect($response->json())->map(fn($brewery) => new self($brewery));
    }
}
