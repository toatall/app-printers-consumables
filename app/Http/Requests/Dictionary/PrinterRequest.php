<?php

namespace App\Http\Requests\Dictionary;

use App\Models\Printer\Printer;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class PrinterRequest extends FormRequest
{

    private $_printer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->_printer = Route::input('printer', new Printer());
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
            'vendor' => [
                'required',
                'max:100',                
                Rule::unique('printers')->where(function (Builder $query) {                    
                    $query->where('vendor', $this?->vendor)
                            ->where('model', $this?->model)
                            ->where('id', '<>', $this?->_printer?->id);
                }),
            ],
            'model' => [
                'required',
                'max:200',
                Rule::unique('printers')->where(function (Builder $query) {                    
                    $query->where('vendor', $this?->vendor)
                            ->where('model', $this?->model)
                            ->where('id', '<>', $this?->_printer?->id);
                }),
            ],
            'is_color_print' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return Printer::labels();
    }
    
}
