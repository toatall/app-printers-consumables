<?php

namespace App\Models\Consumable;

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
 */
class ConsumableCountAdded extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables_counts_added';

    public static function booting()
    {
        parent::booting();
        self::creating(function(self $model) {
            $model->id_author = Auth::user()->id;

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

}
