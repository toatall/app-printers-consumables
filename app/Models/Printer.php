<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $vendor
 * @property string $model
 * @property bool $color_print
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property PrinterConsumable[] $consumables
 */
class Printer extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'printers';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'vendor',
        'model',
        'color_print',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'color_print' => 'boolean',
    ];  

    public static function labels()
    {
        return [
            'vendor' => 'Производитель',
            'model' => 'Модель',
            'color_print' => 'Цветная печать',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }


    /**
     * Производитель и модель
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->vendor} {$this->model}";
    }

    /**
     * Расходные материалы принтера
     * @return HasMany
     */
    public function consumables(): HasMany
    {
        return $this->hasMany(PrinterConsumable::class, 'id_printer', 'id')->orderBy('type_consumable');
    }

    // public static function filter(array $filters)
    // {
    //     return DB::table('printers')            
    //         ->select(
    //             'printers.id',
    //             'printers.vendor',
    //             'printers.model',                
    //             //DB::raw("printers.vendor + ' ' + printers.model as printer_full_name"),
    //             'printers.color_print',
    //             DB::raw('printers.created_at as printer_created_at'),
    //             DB::raw('printers.updated_at as printer_updated_at'),
    //             DB::raw('printers_consumables.id as consumable_id'),
    //             // 'printers_consumables.id_printer',
    //             DB::raw('printers_consumables.type_consumable as type_consumable'),
    //             DB::raw('printers_consumables.name as consumable_name'),
    //             DB::raw('printers_consumables.color as consumable_color'),
    //             DB::raw('printers_consumables.description as consumable_description'),
    //             DB::raw('printers_consumables.count_local as consumable_count_local'),
    //             DB::raw('printers_consumables.count_stock as consumable_count_stock'),
    //             DB::raw('printers_consumables.created_at as consumable_created_at'),
    //             DB::raw('printers_consumables.updated_at as consumable_updated_at'),
    //         )
    //         ->orderBy('printers.vendor')
    //         ->orderBy('printers.model')
    //         ->leftJoin('printers_consumables', 'printers_consumables.id_printer', '=', 'printers.id')    
    //         ->when($filters['search'] ?? null, fn($query, $search) => 
    //             $query->where('printers.vendor', 'like', "%$search%")
    //                 ->orWhere('printers.model', 'like', "%$search%")
    //                 ->orWhere('printers_consumables.name', 'like', "%$search%")
    //                 ->orWhere('printers_consumables.description', 'like', "%$search%")
    //         )
    //         ->get()
    //         ->map(function($val) { 
    //             $val->printer_full_name = "{$val->vendor} {$val->model}";
    //             return $val; 
    //         });
    // }


    /**
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter($query, array $filters)
    {
        $query->with('consumables');
        $query->when($filters['search'] ?? null, function ($query, $search) {            
            $query->where('vendor', 'like', "%$search%")
                ->orWhere('model', 'like', "%$search%")           
                ->orWhereHas('consumables', fn($query) => $query->where('name', 'like', "%$search%"));
        });            
    }
    
}
