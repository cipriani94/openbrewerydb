<?php

namespace App\Http\Requests\Api;

use App\Enums\BreweryType;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use \App\Enums\BrewerySort;

class BeersRequest extends FormRequest
{
    use ApiRequestValidationTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'by_city' => 'nullable|string',
            'by_country' => 'nullable|string',
            'by_dist' => 'nullable|string',
            'by_ids' => 'nullable|string',
            'by_name' => 'nullable|string',
            'by_state' => 'nullable|string',
            'by_postal' => ['nullable', 'string', 'max:10', function ($attribute, $val, $fail) {
                if (preg_match('/^\d{5}$/', $val) === 0 and  preg_match('/^\d{5}-\d{4}$/', $val) === 0) return $fail(__('brewery.validation.by_postal.not_valid'));
            }],
            'by_type' => [Rule::enum(BreweryType::class)],
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable|max:200',
            'sort' => ['nullable', 'string', function ($attribute, $val, $fail) {

                $sort = explode(':', $val);
                if (!isset($sort[1])) return $fail(__('brewery.validation.sort.not_found'));
                if (!in_array($sort[1], ['desc', 'asc'])) return $fail(__('brewery.validation.sort.incorrect'));
                $nameColumn = str_replace('=sort', '', $sort[0]);
                if (!in_array($nameColumn, BrewerySort::all())) return $fail(__('brewery.validation.sort.not_valid'));
            }]
        ];
    }
}
