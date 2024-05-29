<?php

namespace App\Http\Requests\Dictionary;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/**
 * Организация
 */
class OrganizationRequest extends FormRequest
{

    /**
     * @var Organization|null
     */
    private $_organization;    

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {        
        $this->_organization = Route::input('organization');
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
                Rule::unique('organizations', 'code')->ignore($this->_organization?->code, 'code'),
            ],
            'name' => [
                'required',
                'max:200',                
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return Organization::labels();
    }
    
}
