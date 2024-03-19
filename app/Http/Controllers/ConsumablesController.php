<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumableRequest;
use App\Models\CartridgeColors;
use App\Models\ConsumableTypes;
use App\Models\Printer;
use App\Models\PrinterConsumable;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class ConsumablesController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'filters' => Request::all(['search']),
            'consumables' => PrinterConsumable::filter(Request::only(['search']))->get()
                ->transform(fn(PrinterConsumable $consumable) => [
                    'id' => $consumable->id,
                    'type_consumable' => $consumable->type_consumable,
                    'name' => $consumable->name,
                    'color' => $consumable->color,
                    'description' => $consumable->description,
                    'count_local' => $consumable->count_local,
                    'count_stock' => $consumable->count_stock,
                    'created_at' => $consumable->created_at,
                    'updated_at' => $consumable->updated_at,
                    'printer' => $consumable->printer()->get()->transform(fn(Printer $printer) => [
                        'id' => $printer->id,
                        'vendor' => $printer->vendor,                
                        'full_name' => $printer->vendor . ' ' . $printer->model,
                        'model' => $printer->model,
                        'color_print' => $printer->color_print,
                        'created_at' => $printer->created_at,
                        'updated_at' => $printer->updated_at,
                    ])->first(),
                ]),
            'typesConsumables' => ConsumableTypes::asArray(),
            'cartridgeColors' => CartridgeColors::asArray(),
            'labelsConsumable' => PrinterConsumable::labels(),
        ]);
    }

    public function create(Printer $printer)
    {     
        $types = ConsumableTypes::asArray();        
        return Inertia::render('Consumable/Form', [
            'isNew' => true,
            'printer' => $printer,
            'consumable' => new PrinterConsumable([
                'count_local' => 0,
                'count_stock' => 0,
            ]),
            'types' => array_map(fn($key, $value) => ['code' => $key, 'name' => $value], array_keys($types), $types),
            'labels' => PrinterConsumable::labels(),
        ]);
    }

    public function store(ConsumableRequest $request, Printer $printer)
    {
        /** @var PrinterConsumable $consumable */
        $consumable = $printer->consumables()->create($request->only([
            'type_consumable',
            'name',
            'color',
            'description',
        ]));
        $consumable->moves()->create($request->only(['count_local', 'count_stock']));
        return redirect()->route('printers.show', [$printer]);
    }

    public function edit(Printer $printer, PrinterConsumable $consumable)
    {
        $types = ConsumableTypes::asArray();        
        return Inertia::render('Consumable/Form', [
            'isNew' => false,
            'printer' => $printer,
            'consumable' => $consumable,
            'types' => array_map(fn($key, $value) => ['code' => $key, 'name' => $value], array_keys($types), $types),
            'labels' => PrinterConsumable::labels(),
        ]); 
    }

    public function update(ConsumableRequest $request, Printer $printer, PrinterConsumable $consumable)
    {
        $consumableUpdate = $consumable->update($request->only([
            'type_consumable',
            'name',
            'color',
            'description',
        ]));        
        if (!$consumableUpdate) {
            return redirect()->back();
        }        
        return redirect()->route('printers.show', [$printer])->with('success', 'Запись успешно обновлена!');
    }

    public function destroy(Printer $printer, PrinterConsumable $consumable)
    {
        $consumable->delete();
        return redirect()->route('printers.edit', [$printer])->with('success', 'Запись удалена');
    }

    // public function addCount(ConsumableIncomingRequest $request, int $idPrinter, int $idConsumable)
    // {
    //     $printer = Printer::findOrFail($idPrinter);
    //     $consumable = PrinterConsumable::findOrFail($idConsumable);

    //     $consumable->moves()->create([
    //         'type_move' => PrinterConsumableMove::TYPE_MOVE_ADD,
    //         'count_local' => $request->count_local,
    //         'count_stock' => $request->count_stock,
    //         'description' => $request->description,
    //     ]);
    //     return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно сохранены!');
    // }

    // public function takeCount(ConsumableOutgoingRequest $request, int $idPrinter, int $idConsumable)
    // {
    //     $printer = Printer::findOrFail($idPrinter);
    //     $consumable = PrinterConsumable::findOrFail($idConsumable);

    //     $consumable->moves()->create([
    //         'type_move' => PrinterConsumableMove::TYPE_MOVE_TAKE_LOCAL,
    //         'count_local' => $request->count_local * -1,
    //         'count_stock' => 0,
    //         'description' => $request->description,
    //     ]);
    //     return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно сохранены!');
    // }

    // public function moveToLocal(ConsumableMoveToLocalRequest $request, int $idPrinter, int $idConsumable)
    // {
    //     $printer = Printer::findOrFail($idPrinter);
    //     $consumable = PrinterConsumable::findOrFail($idConsumable);

    //     $consumable->moves()->create([
    //         'type_move' => PrinterConsumableMove::TYPE_MOVE_STOCK_TO_LOCAL,
    //         'count_local' => $request->count,
    //         'count_stock' => $request->count * -1,
    //         'description' => $request->description,
    //     ]);
    //     return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно сохранены!');
    // }


    // public function moveHistory(int $idPrinter, int $idConsumable)
    // {
    //     $consumable = PrinterConsumable::findOrFail($idConsumable);        
    //     return $consumable->moves()->get()->transform(fn(PrinterConsumableMove $move) => [
    //         'id' => $move->id,
    //         'user' => $move->user->name,
    //         'count_local' => $move->count_local,
    //         'count_stock' => $move->count_stock,
    //         'description' => $move->description, 
    //         'created_at' => $move->created_at,  
    //     ])->toJson();
    // }

    
    
}
