<?php

namespace App\Http\Requests;

use App\Models\Consumable\ConsumableCountInstalled;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ConsumableCountInstalledRequest extends FormRequest
{
    
    /**
     * @var \App\Models\Consumable\ConsumableCount|null
     */
    private $_consumableCount;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {        
        $this->_consumableCount = Route::input('count');        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_consumable_count' => 'required',
            'id_printer_workplace' => 'required',
            'count' => [
                'required',
                'integer',
                'min:1',
                'max:' . $this->getMaxCount(),
            ],            
        ];
    }

    /**
     * Максимально возможное количество, которое можно вычесть
     * @return int
     */
    private function getMaxCount()
    {
        return $this->_consumableCount?->count ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return ConsumableCountInstalled::labels();
    }

}
