<?php

namespace App\Models\Consumable;

use App\Models\Auth\User;
use App\Models\Printer\PrinterWorkplace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Запись об установке расходного материала
 * 
 * @property int $id
 * @property int $id_consumable_count
 * @property int $id_printer_workplace
 * @property int $id_author
 * @property int $count
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property ConsumableCount $consumableCount
 * @property PrinterWorkplace $printerWorkplace
 * @property User $author
 */
class ConsumableCountInstalled extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables_counts_installed';

    /**
     * {@inheritDoc}
     * 
     * Сохранение данных о количестве в родительской записи 
     * с общим количеством расходных материалов
     * При добавлении уменьшается текущее количество к общему количеству в родительской записи
     * При удалении прибавляется текущее количество от общего количества в родительской записи
     * @param self $model текущий объект
     * @param bool $isPlus (true - если увеличивается, false - если уменьшается) 
     */
    private static function updateModel(self $model, bool $isPlus = true)
    {                
        $consumableCount = $model->consumableCount;
        if ($consumableCount === null) {
            throw new \Exception("Parent record ConsumableCount with id={$model->id_consumable_count} not found!");
        }
        $count = $model->count;
        if (!$isPlus) {
            $count = $count * -1;
        }
        $consumableCount->count += $count;
        $consumableCount->save();
    }

    /**
     * {@inheritDoc}
     */
    public static function booting()
    {
        parent::booting();
         // создание объекта
        static::creating(function(self $model) {
            $model->id_author = Auth::user()->id;            
            self::updateModel($model, false);
        });
        // удаление объекта
        static::deleted(function(self $model) {          
            self::updateModel($model, true);
        });
    }

    /**
     * Родительская запись с общим количеством расходного материала
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumableCount()
    {
        return $this->belongsTo(ConsumableCount::class, 'id_consumable_count');
    }

    /**
     * Автор записи
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'id_author');
    }

    /**
     * Принтер на рабочем месте
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function printerWorkplace()
    {
        return $this->belongsTo(PrinterWorkplace::class, 'id_printer_workplace');
    }

    /**
     * Описание атрибутов
     * @return array
     */
    public static function labels(): array
    {
        return [
            'id_printer_workplace' => 'Принтер',
            'count' => 'Количество',
        ];
    }
    
}
