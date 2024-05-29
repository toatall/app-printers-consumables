<?php

namespace App\Http\Controllers;

use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountAdded;


class ConsumablesCountsAddedController extends Controller
{

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        $this->middleware('role:admin')->only(['destroy']);
    }

    /**
     * Список количества добавленных расходных материалов
     * @param Consumable $consumable расходный материал
     * @param ConsumableCount $count общее количество
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Consumable $consumable, ConsumableCount $count)
    {
        return $count->consumablesAdded()->with('author')->get();
    }

    /**
     * Удаление добавленного количества
     * При удалении записи отнимается удаляемое количество от общего количества
     * @param Consumable $consumable расходный материал
     * @param ConsumableCount $Count общее количество
     * @param ConsumableCountAdded $added добавленное количество
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Consumable $consumable, ConsumableCount $count, ConsumableCountAdded $added)
    {
        $added->delete();
        return redirect()->route('counts.show', [$count])
            ->with('success', 'Запись удалена');
    }
}
