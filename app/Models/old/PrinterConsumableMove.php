<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $id_printer_consumables
 * @property string $type_move
 * @property int $id_user
 * @property int $count_local
 * @property int $count_stock
 * @property string $description
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property PrinterConsumable $consumable
 * @property User $user
 */
class PrinterConsumableMove extends Model
{
    use HasFactory;

    const TYPE_MOVE_ADD = 'ADD';
    const TYPE_MOVE_TAKE_LOCAL = 'TAKE_LOCAL';
    const TYPE_MOVE_STOCK_TO_LOCAL = 'STOCK_TO_LOCAL';


    /**
     * {@inheritDoc}
     */
    protected $table = 'printers_consumables_move';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'type_move',
        'count_local',
        'count_stock',
        'description',
    ];

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function (PrinterConsumableMove $model) {
            $model->id_user = auth()->user()->id;            
        });
        
        static::created(function (PrinterConsumableMove $model) {            
            $consumableModel = $model->consumable;
            if ($consumableModel == null) {
                throw new \Exception('Отсутствует родительская связь!');
            }
            if ($model->count_local <> 0 || $model->count_stock <> 0) {
                $consumableModel->update([
                    'count_local' => $consumableModel->count_local + $model->count_local,
                    'count_stock' => $consumableModel->count_stock + $model->count_stock,
                ]);
            }            
        });

        static::deleted(function(PrinterConsumableMove $model) {
            $consumableModel = $model->consumable;
            if ($consumableModel == null) {
                throw new \Exception('Отсутствует родительская связь!');
            }

            if ($model->count_local <> 0 || $model->count_stock <> 0) {
                $consumableModel->update([
                    'count_local' => $consumableModel->count_local - $model->count_local,
                    'count_stock' => $consumableModel->count_stock - $model->count_stock,
                ]);
            }
        });
    }    

    /**
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->with([
            'consumable' => fn($query) => $query->with('printer'), 
            'user',
        ])
        ->orderByDesc('created_at');

        $query->when($filters['search'] ?? null, function (Builder $query, $search) {            
            $query
            ->whereHas('consumable', fn($query) => $query
                ->where('name', 'like', "%$search%")
                ->orWhereHas('printer', fn($query) => $query
                    ->where('vendor', 'like', "%$search%")
                    ->orWhere('model', 'like', "%$search%")
                )
            )
            ->orWhereHas('user', fn($query) => $query
                ->where('fio', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
            );
        });                      
    } 


    /**
     * @return HasOne
     */
    public function consumable(): HasOne
    {
        return $this->hasOne(PrinterConsumable::class, 'id', 'id_printer_consumables');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

}
