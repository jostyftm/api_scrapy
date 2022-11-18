<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteUpdateRequest extends FormRequest
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
            'url'  =>  'required|url|unique:websites,url,'.$this->website->id
        ];
    }

    /**
     * 
     */
    public function messages()
    {
        return [
            'url.required'  =>  'La url es requerida',
            'url.unique'    =>  'La url ya esta en uso',
            'url.url'       =>  'Url invalida'
        ];
    }
}
