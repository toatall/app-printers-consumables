<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumableCountInstalledRequest;
use App\Models\Consumable\CartridgeColors;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountInstalled;
use App\Models\Consumable\ConsumableTypesEnum;
use Inertia\Inertia;

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
     * Последние установленные расходные материалы
     * @return array
     */
    public function last()
    {
        return [
            'data' => ConsumableCountInstalled::with([
                'consumableCount' => fn($query) => $query->with('consumable'), 
                'printerWorkplace' => fn($query) => $query->with('printer'),
                'author',
            ])->orderByDesc('created_at')->get(),
            'cartridgeColors' => CartridgeColors::get(),
            'consumableTypes' => ConsumableTypesEnum::array(),
        ];
    }  

    /**
     * Сохранение установленного расходного материала
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
