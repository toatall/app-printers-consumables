<?php

namespace App\Http\Requests\Dictionary;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Factory;
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
     * {@inheritDoc}
     */
    public function __construct(Factory $validationFactory)
    {
        $this->_organization = Route::input('organization');        
        
        // вложенность должна быть не более 2 уровней        
        $validationFactory->extend('levelUp', function($attribute, $value, $parameters) {
            if (empty($value)) {
                return true;
            }            
            $parentOrg = Organization::find($value);
            if ($parentOrg !== null && !empty($parentOrg->parent)) {
                return false;
            }            
            return true;
        }, 'Родительская организация является дочерней. Вложенность не должна превышать 2 уровней!');

        // вложенность должна быть не более 2 уровней
        $validationFactory->extend('levelDown', function($attribute, $value, $parameters) {
            if (empty($value)) {
                return true;
            }                    
            $organization = Route::input('organization');
            if ($organization !== null && $organization->childOrganizations()->count() > 0) {
                return false;
            }
            return true;
        }, 'У текущей организации есть дочерние организации. Вложенность не должна превышать 2 уровней!');

        // если выбрана родительская организация, то проверяется ее существование
        $validationFactory->extend('checkRelation', function($attribute, $value, $parameters){
            if (empty($value)) {
                return true;
            }
            $parentOrg = Organization::find($value);
            return $parentOrg ==! null;
        }, 'Родительская организация не найдена');

    }   


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
            'parent' => [
                'levelUp',
                'levelDown',
                'checkRelation',
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
