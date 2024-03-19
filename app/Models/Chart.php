<?php

namespace App\Models;


class Chart 
{
    
    public static function get($year = null)
    {
        $year = $year ?? date('Y');
        return [
            'added' => self::added($year),
            'deducted' => self::deducted($year),
        ];
    }

    protected static function added($year)
    {
        return self::query($year, PrinterConsumableMove::TYPE_MOVE_ADD);
    }

    protected static function deducted($year)
    {
        return self::query($year, PrinterConsumableMove::TYPE_MOVE_TAKE_LOCAL);
    }

    protected static function query($year, $type)
    {        
        return PrinterConsumableMove::query()
            ->selectRaw('SUM(count_local) count_local, DATE(created_at) created_at')
            ->groupByRaw('DATE(created_at)')
            ->whereBetween('created_at', ["$year-01-01", "$year-12-31"])
            ->where('type_move', $type)
            ->get()
            ->transform(fn(PrinterConsumableMove $model) => [
                'date' => $model->created_at,
                'count' => abs($model->count_local),
            ]);
    }

    
}
