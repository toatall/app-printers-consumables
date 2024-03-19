<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrinterRequest;
use App\Models\CartridgeColors;
use App\Models\ConsumableTypes;
use App\Models\Printer;
use App\Models\PrinterConsumable;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class PrintersController extends Controller
{
    public function index()
    {
        return Inertia::render('Printers/Index', [
            'filters' => Request::all(['search']),
            'printers' => Printer::filter(Request::only(['search']))->get()
                ->transform(fn(Printer $printer) => [
                    'id' => $printer->id,
                    'vendor' => $printer->vendor,
                    'model' => $printer->model,
                    'color_print' => $printer->color_print,
                    'full_name' => "{$printer->vendor} {$printer->model}",
                    'consumables' => $printer->consumables,
                ]),
            'labels' => Printer::labels(),
            'typesConsumables' => ConsumableTypes::asArray(),
            'cartridgeColors' => CartridgeColors::asArray(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Printers/Create', [
            'labels' => Printer::labels(),           
        ]); 
    }

    public function store(PrinterRequest $request) 
    {        
        $printer = Printer::create($request->only(['vendor', 'model', 'color_print']));
        if (!$printer) {
            return redirect()->back();
        }
        return redirect()->route('printers.show', [$printer]);
    }

    public function show(Printer $printer)
    {
        return Inertia::render('Printers/Show', [
            'printer' => $printer->toArray(),
            'labelsPrinter' => Printer::labels(),
            'consumables' => $printer->consumables()->get()->transform(fn(PrinterConsumable $consumable) => [
                'id' => $consumable->id,
                'type_consumable' => $consumable->type_consumable,
                'name' => $consumable->name,
                'color' => $consumable->color,
                'description' => $consumable->description,
                'count_local' => $consumable->count_local,
                'count_stock' => $consumable->count_stock,
            ]),
            'labelsConsumable' => PrinterConsumable::labels(),
            'typesConsumables' => ConsumableTypes::asArray(),
            'cartridgeColors' => CartridgeColors::asArray(),
        ]);
    }

    public function edit(Printer $printer)
    {        
        return Inertia::render('Printers/Edit', [
            'printer' => $printer->toArray(),
            'labels' => Printer::labels(),
            'consumables' => $printer->consumables()->get()->transform(fn(PrinterConsumable $consumable) => [
                'id' => $consumable->id,
                'type_consumable' => $consumable->type_consumable,
                'name' => $consumable->name,
                'color' => $consumable->color,
                'description' => $consumable->description,
            ]),
        ]);
    }

    public function update(Printer $printer, PrinterRequest $request)
    {       
        $printerUpdate = $printer->update($request->only(['vendor', 'model', 'color_print']));
        if (!$printerUpdate) {
            return redirect()->back();
        }        
        return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно обновлены.');
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();
        return redirect()->route('printers')->with('success', 'Запись удалена');
    }

}