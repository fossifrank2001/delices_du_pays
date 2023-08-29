<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequestForm extends FormRequest
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
        $rules = [
            'CTE_FIRSTNAME' => ['required'],
            'CTE_LASTNAME' => ['required'],
            'CTE_EMAIL' => ['required', 'email', Rule::unique('users')->ignore($this->route()->parameter('id'), 'CTE_ID_COMPTE')],
            'CTE_PHONE' => ['required', 'string', Rule::unique('users')->ignore($this->route()->parameter('id'), 'CTE_ID_COMPTE')],
            'CTE_TOWN' => ['nullable'],
            'CTE_QUARTER' => ['nullable']
        ];

        // specify the rule for the register method
        if ($this->is('api/auth/register')) {
            $rules['CTE_PASSWORD'] = ['required', 'string', 'confirmed', 'min:6'];
        }

        // specify the rule for the comptes method
        if ($this->is('api/comptes')) {
            $rules['STA_ID_STATUT'] = ['required', 'integer'];
        }

        return $rules;
    }
}
