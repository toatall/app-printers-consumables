<?php

namespace App\Http\Controllers;

use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountAdded;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class ConsumablesCountsAddedController extends Controller
{    

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
        $this->middleware('role:admins')->only(['destroy']);
        if (Auth::user()->hasRole('admin') || $added->id_author !== Auth::user()->id) {
            $added->delete();
            return redirect()->route('counts.show', [$count])
                ->with('success', 'Запись удалена');
        }
        throw new AuthorizationException();
    }
}
