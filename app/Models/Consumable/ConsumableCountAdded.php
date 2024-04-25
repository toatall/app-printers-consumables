<?php

namespace App\Models\Consumable;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Запись о добавлении расходного материала
 * 
 * @property int $id
 * @property int $id_consumable_count
 * @property int $id_author
 * @property int $count
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property ConsumableCount $consumableCount
 * @property User $author
 */
class ConsumableCountAdded extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables_counts_added';

    /**
     * {@inheritDoc}
     * 
     * Сохранение данных о количестве в родительской записи 
     * с общим количеством расходных материалов
     * При добавлении увеличивается текущее количество к общему количеству в родительской записи
     * При удалении уменьшается текущее количество от общего количества в родительской записи
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
            self::updateModel($model);
        });

        // удаление объекта
        static::deleted(function(self $model) {            
            self::updateModel($model, false);
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

}
