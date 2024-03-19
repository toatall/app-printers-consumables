<?php

namespace App\Http\Requests;

use App\Models\PrinterConsumable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ConsumableIncomingRequest extends FormRequest
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
            'count_local' => [
                'required',
                'integer',
                'max:1000',
                Rule::when($this?->count_stock == 0, 'min:1', 'min:0'),
            ],
            // 'count_stock' => [
            //     'required', 
            //     'integer',
            //     'max:1000',  
            //     Rule::when($this?->count_local == 0, 'min:1', 'min:0'),
            // ],
        ];
    }

    
    public function attributes()
    {
        return PrinterConsumable::labels();
    }
}
