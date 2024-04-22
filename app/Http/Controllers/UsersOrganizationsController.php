<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class UsersOrganizationsController extends Controller
{
    
    public function index()
    {       
        return [
            'organizations' => Auth::user()->availableOrganizations(),
            'organizationLabels' => Organization::labels(),
        ];
    }

    public function change(Organization $organization)
    {                
        if (Auth::user()->availableOrganizations()->where('code', $organization->code)->count()) {
            Auth::user()->changeSelectedOrganization($organization->code);
            return redirect()->back()
                ->with('success', "Выбрана организация с кодом {$organization->code}!");       
        }
    }

}
