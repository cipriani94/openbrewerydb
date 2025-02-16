<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BeersRequest;
use App\Services\BreweryService;

class BreweriesController extends Controller
{


    public function index(BeersRequest $beersRequest)
    {
        $beersRequestValidated = $beersRequest->validated();
        $breweryService = new BreweryService;
        $breweryResponse = $breweryService->list($beersRequestValidated ?? []);
        if (is_null($breweryResponse) and empty($breweryCollection)) return response()->api(404, false, null, __('brewery.not_found'));

        return response()->api(200, true, $breweryResponse->breweries, null, null, $breweryResponse->meta);
    }
}
