<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!$user = auth()->user()) {
            return false;
        }
        
        return $user->docs_access;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
