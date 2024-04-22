<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrinterWorkplaceRequest;
use App\Models\Printer\Printer;
use App\Models\Printer\PrinterWorkplace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PrintersWorkplaceController extends Controller
{

    private function allPrinters()
    {
        return Printer::all()->transform(fn(Printer $printer) => [
            'id' => $printer->id,
            'name' => "$printer->vendor $printer->model",
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Printers/Index', [
            'filters' => Request::all(['search']),
            'printersWorkplace' => PrinterWorkplace::filter(Request::only(['search']))->get(), 
            'printerWorkplaceLabels' => PrinterWorkplace::labels(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Printers/Create', [
            'labels' => PrinterWorkplace::labels(),      
            'printers' => $this->allPrinters(),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */
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
        return redirect()->route('workplace.index')
            ->with('success', 'Запись успешно добавлена!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PrinterWorkplace $workplace)
    {                
        return Inertia::render('Printers/Show', [
            'printerWorkplace' => $workplace,
            'printerWorkplace.printer' => $workplace->printer,
            'printerWorkplace.author' => $workplace->author,
            'printerLabels' => Printer::labels(),
            'printerWorkplaceLabels' => PrinterWorkplace::labels(),            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrinterWorkplace $workplace)
    {
        return Inertia::render('Printers/Edit', [
            'printerWorkplace' => $workplace,
            'printerWorkplace.printer' => $workplace->printer,
            'printers' => $this->allPrinters(),
            'labels' => PrinterWorkplace::labels(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrinterWorkplaceRequest $request, PrinterWorkplace $workplace)
    {
        $workplaceUpdate = $workplace->update($request->only(['id_printer', 'location', 'serial_number', 'inventory_number']));        
        if (!$workplaceUpdate) {
            return redirect()->back();
        }
        return redirect()->route('workplace.show', $workplace)
            ->with('success', 'Запись успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrinterWorkplace $workplace)
    {
        $workplace->delete();
        return redirect()->route('workplace.index')
            ->with('success', 'Запись успешно удалена!');
    }
}
