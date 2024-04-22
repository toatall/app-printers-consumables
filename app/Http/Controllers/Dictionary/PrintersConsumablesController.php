<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\Consumable\CartridgeColors;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableTypesEnum;
use App\Models\Printer\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PrintersConsumablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Printer $printer)
    {
        return Inertia::render('Dictionary/Printers/Consumables/Index', [
            'consumables' => $printer->consumablesNotIn()->filter(Request::only(['search']))->get(),
            'filters' => Request::all(['search']),
            'printer' => $printer,            
            'consumableTypes' => ConsumableTypesEnum::array(),
            'consumableLabels' => Consumable::labels(),
            'cartridgeColors' => CartridgeColors::get(),
        ]);
    }

    
    public function add(Printer $printer, Consumable $consumable)
    {
        $printer->consumables()->attach($consumable->id, ['id_author' => Auth::id()]);
        return redirect()->route('dictionary.printers.show', [$printer])
            ->with('success', 'Связь успешно добавлена!');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Printer $printer, Consumable $consumable)
    {
        $printer->consumables()->detach($consumable->id);
        return redirect()->route('dictionary.printers.show', [$printer])
            ->with('success', 'Связь успешно удалена!');        
    }

}
