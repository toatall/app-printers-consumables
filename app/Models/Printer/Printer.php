<?php

namespace App\Models\Printer;

use App\Models\Auth\User;
use App\Models\Consumable\Consumable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $vendor
 * @property string $model
 * @property bool $is_color_print
 * @property int $id_author
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property User $author
 * @property Consumable[] $consumables
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
        'is_color_print',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'is_color_print' => 'boolean',
    ];    

    public static function boot()
    {
        parent::boot();
        self::creating(function(Printer $model) {
            $model->id_author = Auth::id();
        });
        // self::updating(function(Printer $model){
        //     $model->id_author = Auth::id();
        // });
    }

    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'vendor' => 'Производитель',
            'model' => 'Модель',
            'is_color_print' => 'Цветная печать',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'author' => 'Автор',
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
     * @return BelongsToMany
     */
    public function consumables(): BelongsToMany
    {        
        return $this->belongsToMany(Consumable::class, 'printers_consumables', 'id_printer', 'id_consumable');   
    }

    public function consumablesNotIn()
    {
        return Consumable::whereDoesntHave('printers', fn(Builder $query) => 
            $query->where('printers.id', $this->id)
        ); 
    }

    /**
     * Автор
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_author');
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
    public function scopeFilter(Builder $query, array $filters)
    {               
        $query->with('author');
        $query->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->whereAny(['vendor', 'model'], 'ILIKE', "%$search%");
        });
        $query->orderByDesc('created_at')->orderByDesc('updated_at');
    }
    
}
