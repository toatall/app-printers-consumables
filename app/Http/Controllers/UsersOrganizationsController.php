<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

/**
 * Управление списком организаций привязанных к текущему пользователю Auth::user()
 */
class UsersOrganizationsController extends Controller
{
    
    /**
     * Список организаций привязанных к текущему пользователю
     * @return array
     */
    public function index()
    {
        return [
            'organizations' => Auth::user()->availableOrganizations(),
            'organizationLabels' => Organization::labels(),
        ];
    }

    /**
     * Установка списка организаций @param Organization $organization
     * текущему пользователю
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function change(Organization $organization)
    {                
        if (Auth::user()->isAvailableByOrgCode($organization->code)) {
            Auth::user()->changeSelectedOrganization($organization->code);
            return redirect()->back()
                ->with('success', "Выбрана организация с кодом {$organization->code}!");       
        }
    }

}
