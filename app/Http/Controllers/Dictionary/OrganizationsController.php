<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dictionary\OrganizationRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Управление организациями
 */
class OrganizationsController extends Controller
{
    /**
     * Список организаций
     * @return \Inertia\Response
     */
    public function index()
    {        
        return Inertia::render('Dictionary/Organizations/Index', [
            'organizations' => Organization::all(), 
            'labels' => Organization::labels(),
        ]);
    }

    /**
     * Добавление организации
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Dictionary/Organizations/Create', [
            'labels' => Organization::labels(),
        ]);
    }

    /**
     * Сохранение новой организации
     * @param OrganizationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create($request->only(['code', 'name']));
        if (!$organization) {
            return redirect()->back();
        }
        return redirect()->route('dictionary.organizations.index')
            ->with('success', 'Запись успешно добавлена!');
    }

    /**
     * Детальная информация об организации $organization
     * @param Organization $organization
     * @return \Inertia\Response
     */
    public function show(Organization $organization)
    {
        return Inertia::render('Dictionary/Organizations/Show', [
            'organization' => $organization,
            'labels' => Organization::labels(),
        ]);
    }

    /**
     * Редактирование организации $organization
     * @param Organization $organization
     * @return \Inertia\Response
     */
    public function edit(Organization $organization)
    {        
        return Inertia::render('Dictionary/Organizations/Edit', [
            'organization' => $organization,
            'labels' => Organization::labels(),
        ]);
    }

    /**
     * Сохранение отредактированной организации $organization
     * @param Organization $organization
     * @param OrganizationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organizationUpdate = $organization->update($request->only(['code', 'name']));        
        if (!$organizationUpdate) {
            return redirect()->back();
        }        
        return redirect()->route('dictionary.organizations.index')
            ->with('success', 'Запись успешно обновлена!');
    }

    /**
     * Удаление организации $organization
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return Redirect::back()->with('success', 'Organization deleted.');
    }

}
