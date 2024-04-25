<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumableCountInstalledRequest;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountInstalled;

class ConsumablesCountsInstalledController extends Controller
{
    /**
     * Список количества добавленных расходных материалов
     * @param Consumable $consumable расходный материал
     * @param ConsumableCount $count общее количество
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Consumable $consumable, ConsumableCount $count)
    {
        return $count->consumablesInstalled()->with('author')->get();
    }

    /**
     * Store a newly created 
     * 
     * resource in storage.
     */
    public function store(Consumable $consumable, ConsumableCount $count, ConsumableCountInstalledRequest $request)
    {        
        $consumableCountInstalled = new ConsumableCountInstalled($request->only(['id_consumable_count', 'id_printer_workplace', 'count']));
        if (!$consumableCountInstalled->save()) {
            return redirect()->back()->with('error', 'Возникла ошибка при сохранении!');
        }
        return redirect()->back()->with('success', 'Данные успешно сохранены!');
    }

    /**
     * Удаление добавленного количества
     * При удалении записи отнимается удаляемое количество от общего количества
     * @param Consumable $consumable расходный материал
     * @param ConsumableCount $Count общее количество
     * @param ConsumableCountInstalled $installed установленное количество
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Consumable $consumable, ConsumableCount $count, ConsumableCountInstalled $installed)
    {
        $installed->delete();
        return redirect()->route('counts.show', [$count])
            ->with('success', 'Запись удалена');
    }

}
