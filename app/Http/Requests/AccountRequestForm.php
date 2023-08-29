<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequestForm extends FormRequest
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
            // Define your validation rules here
            'CTE_FIRSTNAME'=> ['required'],
            'CTE_LASTNAME'=> ['required'],
            'CTE_EMAIL'=> ['required','email', Rule::unique('users')->ignore($this->route()->parameter('id'), 'CTE_ID_COMPTE')],
            'CTE_PHONE'=> ['required', Rule::unique('users')->ignore($this->route()->parameter('id'), 'CTE_ID_COMPTE')],
            'STA_ID_STATUT'=> ['required','integer'],
            'CTE_TOWN' => ['nullable'],
            'CTE_QUARTER' => ['nullable']
        ];
    }
}
