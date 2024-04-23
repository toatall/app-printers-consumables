<?php

namespace App\Models\Consumable;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
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
class ConsumableCountInstalled extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables_counts_installed';

    public static function booting()
    {
        parent::booting();
        
        static::creating(function(self $model) {
            $model->id_author = Auth::user()->id;

            $consumableCount = $model->consumableCount;
            if ($consumableCount === null) {
                throw new \Exception("Parent record ConsumableCount with id={$model->id_consumable_count} not found!");
            }
            $consumableCount->count -= $model->count;
            $consumableCount->save();
        });

        static::deleted(function(self $model) {
            $consumableCount = $model->consumableCount;
            if ($consumableCount === null) {
                throw new \Exception("Parent record ConsumableCount with id={$model->id_consumable_count} not found!");
            }
            $consumableCount->count += $model->count;
            $consumableCount->save();
        });
    }

    public function consumableCount()
    {
        return $this->belongsTo(ConsumableCount::class, 'id_consumable_count');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'id_author');
    }
    
}
