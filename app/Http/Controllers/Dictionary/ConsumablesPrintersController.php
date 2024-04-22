<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableTypesEnum;
use App\Models\Printer\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ConsumablesPrintersController extends Controller
{
    /**
     * Display a listing of the resource.
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


    public function add(Consumable $consumable, Printer $printer)
    {
        $consumable->printers()->attach($printer->id, ['id_author' => Auth::id()]);
        return redirect()->route('dictionary.consumables.show', [$consumable])
            ->with('success', 'Связь успешно добавлена!');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Consumable $consumable, Printer $printer)
    {
        $consumable->printers()->detach($printer->id);
        return redirect()->route('dictionary.consumables.show', [$consumable])
            ->with('success', 'Связь успешно удалена!');        
    }
}
