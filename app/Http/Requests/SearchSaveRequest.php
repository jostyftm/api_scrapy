<?php

namespace App\Http\Requests;

use App\Rules\VerifySearch;
use Illuminate\Foundation\Http\FormRequest;

class SearchSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'query'         =>  ['required', new VerifySearch],
            'website_id'    =>  'required',
        ];
    }

    /**
     * 
     */
    public function messages()
    {
        return [
            'query.required'        =>  'La consulta es requerida',
            'website_id.required'   =>  'El sitio es requerido'
        ];
    }
}