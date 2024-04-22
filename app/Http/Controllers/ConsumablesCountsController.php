<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumableCountRequest;
use App\Models\Consumable\CartridgeColors;
use App\Models\Consumable\Consumable;
use App\Models\Consumable\ConsumableCount;
use App\Models\Consumable\ConsumableCountAdded;
use App\Models\Consumable\ConsumableTypesEnum;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ConsumablesCountsController extends Controller
{

    /**
     * Поиск всех расходных материалов (для списка)
     * @use ConsumablesCountsController::index()
     * @return \Illuminate\Support\Collection
     */
    private function allConsumables()
    {
        return Consumable::all()->transform(fn(Consumable $consumable) => [
            'id' => $consumable->id,
            'name' => ConsumableTypesEnum::getValueByName($consumable->type) . ' ' . $consumable->name
                . ($consumable->type === 'cartridge' ? ' (' . (CartridgeColors::get()[$consumable->color]['name'] ?? $consumable->color) . ')' : ''),
        ]);
    }

    /**
     * Список записей о количестве расходных материалов
     * @return \Inertia\Response
     */
    public function index()
    {        
        $consumablesCounts = ConsumableCount::filter(Request::only(['search', 'consumableType']))->get();
            // ->get()->transform(fn(ConsumableCount $consumableCount) => [
            //     'id' => $consumableCount,
            //     'count' => $consumableCount->count,
            //     'consumable' => $consumableCount->consumable()->get()->transform(fn(Consumable $consumable) => [
            //         'id' => $consumable->id,
            //         'type' => $consumable->type,
            //         'name' => $consumable->name,
            //         'color' => $consumable->color,
            //         'title' => $consumable->title(),
            //     ]),
            // ]);
        return Inertia::render('Consumable/Count/Index', [
            'filters' => Request::all(['search', 'consumableType']),
            'consumablesCounts' => $consumablesCounts,
            'consumableLabels' => Consumable::labels(),    
            'consumableCountLabels' => ConsumableCount::labels(),
            'consumableTypes' => ConsumableTypesEnum::array(),      
            'cartridgeColors' => CartridgeColors::get(),
        ]);
    }        

    /**
     * Форма создания записи ConsumableCount
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Consumable/Count/Create', [
            'consumableLabels' => Consumable::labels(),
            'consumableCountLabels' => ConsumableCount::labels(),
            'consumables' => $this->allConsumables(),
            'availableOrganizations' => Auth::user()->availableOrganizations(),
        ]);
    }

    /**
     * Сохранение (добавление/изменение) ConsumableCount
     * 
     * По выбранному идентификатору расходного материала (id_consumable) и текущей организации Auth::user()->org_code выполняется поиск
     * Если запись найдена, то для добавления используется она, в противном случае создается новая запись
     * 
     * В случае, если запись найдена и указано о необходимости обновления списка организаций (changeOrganization == true),
     * то список организаций будет заменен на selectedOrganizations
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(ConsumableCountRequest $request)
    {
        // получение данных из формы запроса
        $organizations = $request->get('selectedOrganizations');
        $idConsumable = $request->get('id_consumable');
        $count = $request->get('count');
        $changeOrganization = $request->get('changeOrganization', false);


        DB::beginTransaction();
        $isNew = false;

        // поиск или создание записи о количестве расходных материалов
        // поиск выполняется по id_consumable и наличию текущей организации Auth::user()->org_code
        //$consumableCount = ConsumableCount::findByIdConsumableAndOrgs($idConsumable, $organizations);
        $consumableCount = ConsumableCount::findByIdConsumable($idConsumable);
        // если запись не найдена, то создается новая
        if (!$consumableCount) {
            $isNew = true;
            $consumableCount = new ConsumableCount([
                'id_consumable' => $idConsumable,
                'count' => 0,
            ]);
            $consumableCount->save();           
        }
        
        // изменение списка организаций
        // если новая запись или если было указано, что необходимо изменить список организаций
        if ($isNew || $changeOrganization) {
            $consumableCount->updateOrganizations($organizations);
        }  

        // создание модели ConsumableCountAdded с добавляемым количеством count
        $consumableCountAdded = new ConsumableCountAdded([
            'id_consumable_count' => $consumableCount->id,
            'count' => $count,
        ]);

        // результаты выполнения
        if (!$consumableCountAdded->save()) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Возникла ошибка при сохранении!');
        }
        DB::commit();
        return redirect()->route('consumables.counts')
            ->with('success', 'Данные успешно сохранены!');
    }

    /**
     * Поиск записи ConsumableCount с переданным id_consumable
     * @return array|null
     */
    public function checkExists()
    {
        $idConsumable = Request::get('id_consumable');
        if ($idConsumable === null) {
            abort(500, 'Attribute id_consumable is null');
        }

        $consumableCount = ConsumableCount::findByIdConsumable($idConsumable);
        if ($consumableCount !== null) {
            return [
                'id' => $consumableCount->id,
                'count' => $consumableCount->count,
                'organizations' => $consumableCount->organizations(),
            ];
        }   
    }    

    /**
     * Валидация данных при добавлении нового документа ConsumableCount
     */
    public function validateConsumableCount(ConsumableCountRequest $request, $step) { }

    /**
     * Отображение информации о количестве по расходному материалу $consumableCount
     * 
     * @return \Inertia\Response
     */
    public function show(ConsumableCount $consumableCount)
    {        
        $consumable = $consumableCount->consumable;
        return Inertia::render('Consumable/Count/Show', [
            'consumableCount' => $consumableCount,
            'consumable' => $consumable,
            'consumableTitle' => $consumable->title(),
            'consumableCountLabels' => ConsumableCount::labels(),
            'organizations' => $consumableCount->organizations(),
        ]);
    }

}
