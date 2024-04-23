<?php

namespace App\Http\Controllers;

use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountInstalled;
use Illuminate\Http\Request;

class ConsumablesCountsInstalledController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Consumable $consumable, ConsumableCount $count)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsumableCountInstalled $consumableCountInstalled)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsumableCountInstalled $consumableCountInstalled)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsumableCountInstalled $consumableCountInstalled)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsumableCountInstalled $consumableCountInstalled)
    {
        //
    }
}
