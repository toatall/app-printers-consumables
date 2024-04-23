<?php

namespace App\Models\Consumable;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Printer\Printer;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Расходный материал
 * 
 * @property int $id
 * @property int $id_author
 * @property string $type
 * @property string $name
 * @property string $color
 * @property string $description
 * @property bool $arch
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property User $author
 * @property Printer[] $printers
 */
class Consumable extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * {@inheritDoc}
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function(Consumable $model) {
            $model->id_author = Auth::id();
        });
    }

    /**
     * {@inheritDoc}
     */
    protected $table = 'consumables';
    
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'type',
        'name',
        'color',
        'description',
        'arch',
    ];


    /**
     * Автор записи
     * @return HasOne
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'id_author');
    }    

    /**
     * Принтеры привязанные к этому расходному материалу
     * @return BelongsToMany
     */
    public function printers(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class, 'printers_consumables', 'id_consumable', 'id_printer');        
    }

    /**
     * Принтеры не привязанные к этому расходному материалу
     * @return Builder
     */
    public function printersNotIn()
    {        
        return Printer::whereDoesntHave('consumables', fn(Builder $query) => 
            $query->where('consumables.id', $this->id)
        );
    }

    /**
     * Фильтр
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter(Builder $query, array $filters)
    {               
        $query->with('author');
        $query->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->whereAny(['name'], 'ILIKE', "%$search%");
        });
        $query->orderByDesc('created_at')->orderByDesc('updated_at');
    }

    /**
     * Описание атрибутов
     * @return array
     */
    public static function labels()
    {
        return [
            'type' => 'Вид',
            'name' => 'Наименование (модель)',
            'description' => 'Описание',
            'color' => 'Цвет',
            'date' => 'Дата',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }

    /**
     * Описание расходного материала (с указанием типа, наименование и цветом (если картридж))
     * @return string
     */
    public function title()
    {
        $title = ConsumableTypesEnum::getValueByName($this->type) . ' ' . $this->name;
        if ($this->type === 'cartridge') {
            $title .= ' (' . CartridgeColors::get()[$this->color]['name'] ?? $this->color . ')';
        }
        return $title;
    }

}
