<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BeveurageRequestForm extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ART_NAME' => ['required', 'string', Rule::unique('articles')->ignore($this->route()->parameter('id'), 'ART_ID_ARTICLE')],
            'ART_PRICE' => ['required', 'numeric', 'min:0'], // Updated to 'numeric' to accept decimal values
            'ART_DESCRIPTION' => ['nullable'],
            'ART_QUANTITY' => ['required', 'integer', 'min:0'],
            'ART_NOTE' => ['nullable'],
            'BEV_IS_ALCOHOLIC' => ['required', 'boolean'], // Updated to 'boolean' to accept boolean values (0 or 1)
            'BEV_DEGREE_ALCOHOLIC' => ['nullable', 'numeric', 'min:0'], // Assuming 'BEV_DEGREE_ALCOHOLIC' is numeric and should be non-negative
        ];
    }
}
