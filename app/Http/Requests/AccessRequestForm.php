<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequestForm extends FormRequest
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
            "CTE_ID_COMPTE" => ['required', 'exist:users,CTE_ID_COMPTE'],
            "ROL_ID_ROLE" => ['required', 'exist:roles,ROL_ID_ROLE'],
            "STA_ID_STATUT" => ['required', 'exist:statuts,STA_ID_STATUT'],
        ];
    }
}
