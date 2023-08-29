<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequestForm extends FormRequest
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
            'ROL_LIBELLE'=> ['required', 'string', Rule::unique('roles', 'ROL_LIBELLE')->ignore($this->route('role'), 'ROL_ID_ROLE')],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,PER_ID_PERMISSION'],
        ];
    }
}
