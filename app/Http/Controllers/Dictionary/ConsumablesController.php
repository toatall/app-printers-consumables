<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dictionary\ConsumableRequest;
use App\Models\Consumable\CartridgeColors;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableTypesEnum;
use App\Models\Printer\Printer;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ConsumablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        return Inertia::render('Dictionary/Consumables/Index', [
            'consumables' => Consumable::filter(Request::only(['search']))->get(),
            'filters' => Request::all(['search']),
            'consumableTypes' => ConsumableTypesEnum::array(),
            'cartridgeColors' => CartridgeColors::get(),
            'labels' => Consumable::labels(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dictionary/Consumables/Create', [
            'labels' => Consumable::labels(),
            'cartridgeColors' => CartridgeColors::get(),
            'consumableTypes' => ConsumableTypesEnum::array(),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsumableRequest $request)
    {
        $consumable = Consumable::create($request->only(['type', 'name', 'color', 'description']));
        if (!$consumable) {
            return redirect()->back();
        }
        return redirect()->route('dictionary.consumables.index')
            ->with('success', 'Запись успешно добавлена!');
    }

    public function show(Consumable $consumable)
    {
        return Inertia::render('Dictionary/Consumables/Show', [            
            'consumable' => [
                'id' => $consumable->id,
                'type' => $consumable->type,
                'name' => $consumable->name,
                'color' => $consumable->color,
                'author' => $consumable->author,
                'created_at' => $consumable->created_at,
                'updated_at' => $consumable->updated_at,
            ],
            'cartridgeColors' => CartridgeColors::get(),
            'consumableTypes' => ConsumableTypesEnum::array(),
            'consumableLabels' => Consumable::labels(),
            'consumableTypeValue' => ConsumableTypesEnum::getValueByName($consumable->type),

            'printersNotIn' => $consumable->printersNotIn()->get(),
            'printers' => $consumable->printers,
            'printerLabels' => Printer::labels(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumable $consumable)
    {
        return Inertia::render('Dictionary/Consumables/Edit', [
            'labels' => Consumable::labels(),
            'consumable' => $consumable,
            'cartridgeColors' => CartridgeColors::get(),
            'consumableTypes' => ConsumableTypesEnum::array(),
            'consumableTypeValue' => ConsumableTypesEnum::getValueByName($consumable->type),
        ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConsumableRequest $request, Consumable $consumable)
    {
        $consumableUpdate = $consumable->update($request->only(['type', 'name', 'color', 'description']));
        if (!$consumableUpdate) {
            return redirect()->back();
        }
        return redirect()->route('dictionary.consumables.index')
            ->with('success', 'Запись успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumable $consumable)
    {
        $consumable->delete();
        return redirect()->route('dictionary.consumables.index')
            ->with('success', 'Запись успешно удалена!');
    }
}
