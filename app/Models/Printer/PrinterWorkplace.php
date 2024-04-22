<?php

namespace App\Models\Printer;

use App\Models\Auth\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $id_printer
 * @property int $id_author
 * @property string $org_code
 * @property string $location
 * @property string $serial_number
 * @property string $inventory_number
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property Printer $printer
 * @property Organization $organization
 * @property User $author
 */
class PrinterWorkplace extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'printers_workplace';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id',
        'id_printer',
        'location',
        'serial_number',
        'inventory_number',
    ];

    /**
     * {@inheritDoc}
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function(self $model) {
            $model->id_author = Auth::id();
        });       
    }

    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'id_printer' => 'Принтер',
            'org_code' => 'Код организации',
            'location' => 'Кабинет',
            'serial_number' => 'Серийный номер',
            'inventory_number' => 'Инвентарный номер',
            'date' => 'Дата',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }


    public function printer()
    {
        return $this->belongsTo(Printer::class, 'id_printer');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_code');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'id_author');
    }

    /**
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter(Builder $query, array $filters)
    {   
        $query
            ->with(['printer'])     
            ->where('org_code', Auth::user()->org_code)      
            ->when($filters['search'] ?? null, function (Builder $query, $search) {
                $query
                    ->whereAny(['location', 'serial_number', 'inventory_number'], 'ILIKE', "%$search%")
                    ->orWhereHas('printer', fn($query) => 
                        $query->whereAny(['vendor', 'model'], 'ILIKE', "%$search%")
                    );
            })
            ->orderByDesc('created_at')
            ->orderByDesc('updated_at');
    }

}
