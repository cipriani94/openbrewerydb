<?php

namespace App\Models\External;

use Illuminate\Support\Facades\Http;

class Meta extends AbstractExternalModel
{

    public static function get(string $urlPrefix, array $query = []): self
    {
        $response = Http::openbrewerydb()->get($urlPrefix . '/meta', $query);
        if ($response->failed()) return new self;
        return new self($response->json());
    }
}
