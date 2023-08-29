<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentFormRequest extends FormRequest
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
            "COM_CONTENT" => ['required', 'string', 'min:5'],
            "COMMENTABLE_type" => ['required'],
            "COMMENTABLE_id" => ['required'],
        ];

        // If the request is for updating, remove the specific rules
        if ($this->isMethod('PUT')) {
            unset($rules['COMMENTABLE_type']);
            unset($rules['COMMENTABLE_id']);
            unset($rules['CTE_ID_COMPTE']);
        }

        return $rules;
    }
}
