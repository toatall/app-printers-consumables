<?php

namespace App\Http\Controllers;

use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountAdded;

class ConsumablesCountsAddedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Consumable $consumable, ConsumableCount $count)
    {        
        return $count->consumablesAdded()->with('author')->get();
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumable $consumable, ConsumableCount $count, ConsumableCountAdded $added)
    {
        $added->delete();
        return redirect()->route('counts.show', [$count])
            ->with('success', 'Запись удалена');
    }
}
