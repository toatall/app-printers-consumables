<?php

namespace App\Http\Requests\Dictionary;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;


class OrganizationRequest extends FormRequest
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
            'code' => [
                'required',
                'max:5',
                'min:4',
                'unique:organizations,code',
            ],
            'name' => [
                'required',
                'max:200',                
            ],
        ];
    }

    public function attributes(): array
    {
        return Organization::labels();
    }
    
}
