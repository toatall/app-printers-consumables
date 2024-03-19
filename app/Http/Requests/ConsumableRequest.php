<?php

namespace App\Http\Requests;

use App\Models\PrinterConsumable;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class ConsumableRequest extends FormRequest
{
    private $_modelConsumable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->_modelConsumable = Route::input('consumable', new PrinterConsumable());     
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
            'type_consumable' => 'required',
            'name' => [
                'required',
                'max:255',
                Rule::unique('printers_consumables', 'name')
                    ->where(fn(Builder $query) => $query->where('id', '<>', $this?->_modelConsumable?->id)),
            ],
            'color' => new RequiredIf($this->type_consumable == 'cartridge'),
            'count_local' => 'required|integer',
            'count_stock' => 'required|integer',  
        ];
    }

    public function attributes()
    {
        return PrinterConsumable::labels();
    }
}
