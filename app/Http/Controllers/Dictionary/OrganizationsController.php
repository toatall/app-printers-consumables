<?php

namespace App\Http\Controllers\Dictionary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dictionary\OrganizationRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class OrganizationsController extends Controller
{
    public function index()
    {        
        return Inertia::render('Dictionary/Organizations/Index', [
            'organizations' => Organization::all(), 
            'labels' => Organization::labels(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dictionary/Organizations/Create', [
            'labels' => Organization::labels(),
        ]);
    }

    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create($request->only(['code', 'name']));
        if (!$organization) {
            return redirect()->back();
        }
        return redirect()->route('dictionary.organizations.index')
            ->with('success', 'Запись успешно добавлена!');
    }

    public function show(Organization $organization)
    {
        return Inertia::render('Dictionary/Organizations/Show', [
            'organization' => $organization,
            'labels' => Organization::labels(),
        ]);
    }

    public function edit(Organization $organization)
    {        
        return Inertia::render('Dictionary/Organizations/Edit', [
            'organization' => $organization,
            'labels' => Organization::labels(),
        ]);
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organizationUpdate = $organization->update($request->only(['code', 'name']));        
        if (!$organizationUpdate) {
            return redirect()->back();
        }        
        return redirect()->route('dictionary.organizations.index')
            ->with('success', 'Запись успешно обновлена!');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Organization deleted.');
    }

    public function restore(Organization $organization)
    {
        $organization->restore();

        return Redirect::back()->with('success', 'Organization restored.');
    }
}
