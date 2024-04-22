<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    private function dateNow() 
    {
        return DB::raw('NOW()');
    }
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::query()->insert([
            ['code' => '0000', 'name' => 'Org main', 'created_at' => $this->dateNow(), 'updated_at' => $this->dateNow()],
            ['code' => '0001', 'name' => 'Sub org 1', 'created_at' => $this->dateNow(), 'updated_at' => $this->dateNow()],
        ]);
    }
}
