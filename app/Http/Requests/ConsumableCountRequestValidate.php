<?php

namespace App\Http\Requests;


class ConsumableCountRequestValidate extends ConsumableCountRequest
{

    /**
     * @var int|null текущий шаг
     */
    private $step = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $step = $this->input('step');
        if ($step !== null) {
            $this->step = (int) $step;
        }        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {        
        // все правила валидации
        $rules = parent::rules();

        // Шаг 1 - Выбор расходного материала
        if ($this->step === 0) {
            return [
                'id_consumable' => $rules['id_consumable'],
            ];
        }
        // Шаг 2 - Перечень организаций
        if ($this->step === 1) {
            return [
                'selectedOrganizations' => $rules['selectedOrganizations'],
            ];
        }
        // Шаг 3 - Количество
        if ($this->step === 2) {
            return [
                'count' => $rules['count'],
            ];
        }
        return $rules;
    }
    
}
