<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait ApiRequestValidationTrait
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->api(422, false, null, null, $validator->errors()->toArray())
        );
    }
}
