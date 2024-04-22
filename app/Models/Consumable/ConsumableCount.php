<?php

namespace App\Models\Consumable;

use Illuminate\Database\Eloquent\Builder;
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

    // public static function findByIdConsumableAndOrgs($idConsumable, $orgs)
    // {
    //     return self::query()->where('id_consumable', $idConsumable)
    //         ->whereExists(fn($query) => $query
    //             ->from('consumables_counts_organizations')
    //             ->where('id_consumable_count', $idConsumable)
    //             ->whereIn('org_code', $orgs))
    //             ->first();
    // }

    /**
     * Поиск записи по идентификатору и текущей организации
     * @return ConsumableCount|null
     */
    public static function findByIdConsumable($idConsumable)
    {
        return self::query()->where('id_consumable', $idConsumable)
            ->whereExists(fn($query) => $query
                ->from('consumables_counts_organizations')
                ->where('id_consumable_count', $idConsumable)
                ->where('org_code', Auth::user()->org_code)
            )
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
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter(Builder $query, array $filters)
    {               
        $query->with(['consumable'])
            ->whereExists(function ($query) {
                $query->from('consumables_counts_organizations')
                    ->whereColumn('consumables_counts_organizations.id_consumable_count', 'consumables_counts.id')
                    ->where('consumables_counts_organizations.org_code', Auth::user()->org_code);
            });

        $query->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->whereHas('consumable', function($query) use ($search) {
                $query->where('name', 'ILIKE', "%$search%"); 
            });
        });
        $query->when($filters['consumableType'] ?? null, function (Builder $query, $consumableType) {
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
