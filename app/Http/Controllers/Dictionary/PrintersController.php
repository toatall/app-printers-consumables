<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dictionary\PrinterRequest;
use App\Models\Consumable\CartridgeColors;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableTypesEnum;
use App\Models\Printer\Printer;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PrintersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dictionary/Printers/Index', [
            'printers' => Printer::filter(Request::only(['search']))->get(),
            'filters' => Request::all(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dictionary/Printers/Create', [
            'labels' => Printer::labels(),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrinterRequest $request)
    {
        $printer = Printer::create($request->only(['vendor', 'model', 'color_print']));
        if (!$printer) {
            return redirect()->back();
        }
        return redirect()->route('dictionary.printers.index')
            ->with('success', 'Запись успешно добавлена!');
    }    

    public function show(Printer $printer)
    {
        return Inertia::render('Dictionary/Printers/Show', [            
            'printer' => [
                'id' => $printer->id,
                'vendor' => $printer->vendor,
                'model' => $printer->model,
                'is_color_print' => $printer->is_color_print,
                'author' => $printer->author,
                'created_at' => $printer->created_at,
                'updated_at' => $printer->updated_at,
            ],
            'printerLabels' => Printer::labels(),

            'consumables' => $printer->consumables,
            'consumablesNotIn' => $printer->consumablesNotIn()->get(),
            'cartridgeColors' => CartridgeColors::get(),
            'consumableTypes' => ConsumableTypesEnum::array(),
            'consumableLabels' => Consumable::labels(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Printer $printer)
    {
        return Inertia::render('Dictionary/Printers/Edit', [
            'printer' => $printer->toArray(),
            'labels' => Printer::labels(),            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrinterRequest $request, Printer $printer)
    {       
        $printerUpdate = $printer->update($request->only(['vendor', 'model', 'is_color_print']));        
        if (!$printerUpdate) {
            return redirect()->back();
        }        
        return redirect()->route('dictionary.printers.index')
            ->with('success', 'Запись успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        $printer->delete();
        return redirect()->route('dictionary.printers.index')
            ->with('success', 'Запись успешно удалена!');
    }
}
