<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrinterRequest;
use App\Models\CartridgeColors;
use App\Models\ConsumableTypes;
use App\Models\Printer\Printer;
use App\Http\Requests\PrinterWorkplaceRequest;
use App\Models\Printer\PrinterWorkplace;
use App\Models\PrinterConsumable;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class PrintersController extends Controller
{

    private function allPrinters()
    {
        return Printer::all()->transform(fn(Printer $printer) => [
            'id' => $printer->id,
            'name' => "$printer->vendor $printer->model",
        ]);
    }

    public function index()
    {
        return Inertia::render('Printers/Index', [
            'filters' => Request::all(['search']),
            'printersWorkplace' => PrinterWorkplace::filter(Request::only(['search']))->get(), 
            'printerWorkplaceLabels' => PrinterWorkplace::labels(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Printers/Create', [
            'labels' => PrinterWorkplace::labels(),      
            'printers' => $this->allPrinters(),
        ]); 
    }

    public function store(PrinterWorkplaceRequest $request) 
    {                
        $printerWorkplace = PrinterWorkplace::create(
            array_merge(
                ['org_code' => Auth::user()->org_code], 
                $request->only(['id_printer', 'location', 'serial_number', 'inventory_number']),
            )
        );
        if (!$printerWorkplace) {
            return redirect()->back();
        }
        return redirect()->route('printers.index')
            ->with('success', 'Запись успешно добавлена!');
    }

    public function show(PrinterWorkplace $workplace)
    {
        dd($workplace);
        return Inertia::render('Printers/Show', [
            'printerWorkplace' => $workplace,
            'printer' => $workplace->printer,
            'labelsPrinter' => Printer::labels(),
            'printerWorkplaceLabels' => PrinterWorkplace::labels(),            
        ]);
    }

    // public function edit(Printer $printer)
    // {        
    //     return Inertia::render('Printers/Edit', [
    //         'printer' => $printer->toArray(),
    //         'labels' => Printer::labels(),
    //         'consumables' => $printer->consumables()->get()->transform(fn(PrinterConsumable $consumable) => [
    //             'id' => $consumable->id,
    //             'type_consumable' => $consumable->type_consumable,
    //             'name' => $consumable->name,
    //             'color' => $consumable->color,
    //             'description' => $consumable->description,
    //         ]),
    //     ]);
    // }

    // public function update(Printer $printer, PrinterRequest $request)
    // {       
    //     $printerUpdate = $printer->update($request->only(['vendor', 'model', 'color_print']));
    //     if (!$printerUpdate) {
    //         return redirect()->back();
    //     }        
    //     return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно обновлены.');
    // }

    // public function destroy(Printer $printer)
    // {
    //     $printer->delete();
    //     return redirect()->route('printers')->with('success', 'Запись удалена');
    // }

}