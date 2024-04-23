<?php

namespace App\Models\Consumable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $id_consumable
 * @property int $count
 * 
 * @property Consumable $consumable
 * @property ConsumableCountAdded[] $consumablesAdded
 */
class ConsumableCount extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables_counts';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id_consumable',
        'count',
    ];


    /**
     * Родительский расходный материал
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumable()
    {
        return $this->belongsTo(Consumable::class, 'id_consumable');
    }

    public function consumablesAdded()
    {
        return $this->hasMany(ConsumableCountAdded::class, 'id_consumable_count');
    }

    /**
     * Список аттрибутов
     * @return array
     */
    public static function labels()
    {
        return [
            'id_consumable' => 'Расходный материал',
            'count' => 'Количество',
            'selectedOrganizations' => 'Организации',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }  

    /**
     * Поиск записи по идентификатору и текущей организации
     * @return ConsumableCount|null
     */
    public static function findByIdConsumable($idConsumable)
    {
        return self::query()->where('id_consumable', $idConsumable)
            ->whereExists(function($query) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query                
                    ->from('consumables_counts_organizations')
                    ->whereColumn('id_consumable_count', '=', 'consumables_counts.id')
                    ->where('org_code', '=', Auth::user()->org_code);
            })
            ->first();
    }

    /**
     * Обновление списка привязанных организаций
     * @param array $organizations
     */
    public function updateOrganizations($organizations)
    {       
        // удаление уже привязанных организаций 
        DB::table('consumables_counts_organizations')
            ->where('id_consumable_count', $this->id)
            ->delete();
        // привязка переданных организаций
        foreach($organizations as $organization) {
            DB::table('consumables_counts_organizations')->insert([
                'id_consumable_count' => $this->id, 
                'org_code' => $organization,
            ]);
        }
    }

    /**
     * Поисковый фильтр
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filter
     */
    public function scopeFilter($query, array $filters)
    {               
        $query->with(['consumable'])
            ->whereExists(function ($query) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->from('consumables_counts_organizations')
                    ->whereColumn('consumables_counts_organizations.id_consumable_count', 'consumables_counts.id')
                    ->where('consumables_counts_organizations.org_code', Auth::user()->org_code);
            });

        $query->when($filters['search'] ?? null, function ($query, $search) {
            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query->whereHas('consumable', function($query) use ($search) {
                $query->where('name', 'ILIKE', "%$search%"); 
            });
        });
        $query->when($filters['consumableType'] ?? null, function ($query, $consumableType) {
            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query->whereHas('consumable', function($query) use ($consumableType) {
                $query->where('type', $consumableType); 
            });
        });
        $query->orderByDesc('created_at')->orderByDesc('updated_at');
    }

    /**
     * Привязанные организации к текущей записи
     * @return \Illuminate\Support\Collection
     */
    public function organizations()
    {
        return DB::table('consumables_counts_organizations')
            ->where('id_consumable_count', $this->id)
            ->pluck('org_code');
    }

}
