<?php

namespace App\Http\Requests;

use App\Models\Printer\PrinterWorkplace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class PrinterWorkplaceRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {                
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = Route::input('workplace')?->id;        
        return [            
            'id_printer' => 'required',
            'location' => 'required',            
            'inventory_number' => [
                'required',
                Rule::unique('printers_workplace')
                    ->where(function($query) use ($id) {
                        $query->where('org_code', Auth::user()->org_code)
                            ->where('id', '<>', $id);
                    }),                  
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return PrinterWorkplace::labels();
    }
}
