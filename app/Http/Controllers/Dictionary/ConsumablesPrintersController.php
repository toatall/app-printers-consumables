<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableTypesEnum;
use App\Models\Printer\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

/**
 * Привязка расходного материала к принтерам
 */
class ConsumablesPrintersController extends Controller
{
    /**
     * Список привязанных принтеров к расходному материалу
     * @param Consumable $consumable расходный материал
     * @return \Inertia\Response
     */
    public function index(Consumable $consumable)
    {        
        return Inertia::render('Dictionary/Consumables/Printers/Index', [
            'printers' => $consumable->printersNotIn()->filter(Request::only(['search']))->get(),
            'filters' => Request::all(['search']),
            'consumable' => $consumable,
            'consumableTypes' => ConsumableTypesEnum::array(),
            'consumableTypeValue' => ConsumableTypesEnum::getValueByName($consumable->type),
        ]);
    }

    /**
     * Привязка принтера $printer с расходным материалом $consumable
     * @param Printer $printer 
     * @param Consumable $consumable
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Consumable $consumable, Printer $printer)
    {
        $consumable->printers()->attach($printer->id, ['id_author' => Auth::id()]);
        return redirect()->route('dictionary.consumables.show', [$consumable])
            ->with('success', 'Связь успешно добавлена!');
    }

    /**
     * Удаление привязки принтера $printer с расходным материалом $consumable
     * @param Printer $printer
     * @param Consumable $consumable
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Consumable $consumable, Printer $printer)
    {
        $consumable->printers()->detach($printer->id);
        return redirect()->route('dictionary.consumables.show', [$consumable])
            ->with('success', 'Связь успешно удалена!');        
    }
}
