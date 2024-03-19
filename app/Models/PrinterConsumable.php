<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $id_printer
 * @property bool $arch
 * @property string $type_consumable
 * @property string $name
 * @property string $color
 * @property string $description
 * @property int $count_local
 * @property int $count_stock
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property Printer $printer
 * @property PrinterConsumableMove[] $moves
 * 
 */
class PrinterConsumable extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'printers_consumables';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id_printer',
        'arch',
        'type_consumable',
        'name',
        'color',
        'description',
        'count_local',
        'count_stock',
    ];

    public static function labels()
    {
        return [
            'type_consumable' => 'Вид',
            'name' => 'Наименование (модель)',
            'description' => 'Описание',
            'color' => 'Цвет',
            'count_local' => 'Количество',
            // 'count_stock' => 'Количество на складе',
        ];
    }

    /**
     * Принтер к которому принадлежит данный ресурс
     * @return HasOne
     */
    public function printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'id_printer', 'id');
    } 

    /**
     * Все движения по этому ресурсу
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(PrinterConsumableMove::class, 'id_printer_consumables', 'id');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->with('printer')
            ->when($filters['search'] ?? null, fn(Builder $query, $search) => 
                $query->where('name', 'like', "%$search%")
                    ->orWhereHas('printer', fn($query) => 
                        $query->where('vendor', 'like', "%$search%")
                            ->orWhere('model', 'like', "%$search%")
                    )
            );

    }
}
