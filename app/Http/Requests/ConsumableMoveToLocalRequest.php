<?php

namespace App\Http\Requests;

use App\Models\PrinterConsumable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class ConsumableMoveToLocalRequest extends FormRequest
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
        $countStock = $this->_modelConsumable?->count_stock ?? 0;
        return [            
            'count' => [
                'required',
                'integer',
                'min:1',                
                Rule::when($this?->count <= $countStock, "max:$countStock", "max:$countStock"),
            ],            
        ];
    }
    
    public function attributes()
    {
        return PrinterConsumable::labels();
    }
}
