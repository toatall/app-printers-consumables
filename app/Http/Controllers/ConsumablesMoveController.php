<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumableIncomingRequest;
use App\Http\Requests\ConsumableMoveToLocalRequest;
use App\Http\Requests\ConsumableOutgoingRequest;
use App\Models\CartridgeColors;
use App\Models\ConsumableTypes;
use App\Models\Printer;
use App\Models\PrinterConsumable;
use App\Models\PrinterConsumableMove;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ConsumablesMoveController extends Controller
{

    public function index()
    {
        // PrinterConsumableMove::filter(Request::only(['search']));
        return Inertia::render('ConsumableMoves/Index', [
            'filters' => Request::all(['search']),
            'moves' => PrinterConsumableMove::filter(Request::only(['search']))->get(),
            'cartridgeColors' => CartridgeColors::asArray(),
            'typesConsumables' => ConsumableTypes::asArray(),
            // ->transform(fn(PrinterConsumableMove $model) => [
            //     'id' => $model->id,
            //     'consumable' => $model->consumable()->get(),                
            // ])
        ]);
    }

    public function add(ConsumableIncomingRequest $request, int $idPrinter, int $idConsumable)
    {        
        $printer = Printer::findOrFail($idPrinter);
        $consumable = PrinterConsumable::findOrFail($idConsumable);

        $consumable->moves()->create([
            'type_move' => PrinterConsumableMove::TYPE_MOVE_ADD,
            'count_local' => $request->count_local,
            'count_stock' => 0,//$request->count_stock,
            'description' => $request->description,
        ]);        
        return $request->session()->flash('success', 'Данные успешно сохранены!');
    }

    public function takeLocal(ConsumableOutgoingRequest $request, int $idPrinter, int $idConsumable)
    {
        $printer = Printer::findOrFail($idPrinter);
        $consumable = PrinterConsumable::findOrFail($idConsumable);

        $consumable->moves()->create([
            'type_move' => PrinterConsumableMove::TYPE_MOVE_TAKE_LOCAL,
            'count_local' => $request->count_local * -1,
            'count_stock' => 0,
            'description' => $request->description,
        ]);
        return $request->session()->flash('success', 'Данные успешно сохранены!');
    }

    public function moveToLocal(ConsumableMoveToLocalRequest $request, int $idPrinter, int $idConsumable)
    {
        $printer = Printer::findOrFail($idPrinter);
        $consumable = PrinterConsumable::findOrFail($idConsumable);

        $consumable->moves()->create([
            'type_move' => PrinterConsumableMove::TYPE_MOVE_STOCK_TO_LOCAL,
            'count_local' => $request->count,
            'count_stock' => $request->count * -1,
            'description' => $request->description,
        ]);
        return redirect()->route('printers.show', [$printer])->with('success', 'Данные успешно сохранены!');
    }

    public function history(int $idPrinter, int $idConsumable)
    {
        /** @var PrinterConsumable $consumable */
        $consumable = PrinterConsumable::findOrFail($idConsumable);        
        return $consumable->moves()->orderByDesc('created_at')
            ->get()->transform(fn(PrinterConsumableMove $move) => [
            'id' => $move->id,
            'type_move' => $move->type_move,           
            'user' => $move->user,
            'count_local' => $move->count_local,
            'count_stock' => $move->count_stock,
            'description' => $move->description, 
            'created_at' => $move->created_at,  
        ])->toJson();
    }

    public function removeHistory(Request $request, int $id)
    {
        $consumableHistory = PrinterConsumableMove::query()->findOrFail($id);
        $consumableHistory->delete();
        return Session::flash('success', 'Запись удалена!'); 
    }

}
    