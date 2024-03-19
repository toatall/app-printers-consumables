<?php

namespace App\Http\Requests;

use App\Models\PrinterConsumable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class ConsumableOutgoingRequest extends FormRequest
{
    /**
     * @var \App\Models\PrinterConsumable
     */
    private $_modelConsumable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {        
        $id = Route::input('consumable');             
        $this->_modelConsumable = PrinterConsumable::query()->where('id', $id)->first();      
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $count = $this->_modelConsumable?->count_local ?? 0;
        return [            
            'count_local' => [
                'required',
                'integer',
                'min:1',
                //'max:100',
                Rule::when($this?->count_local > $count, "max:$count", 'max:100'),
            ],            
        ];
    }

    
    public function attributes()
    {
        return PrinterConsumable::labels();
    }
}
