<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
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
        Role::query()->insert([
            ['name' => 'admin', 'created_at' => $this->dateNow(), 'updated_at' => $this->dateNow()],
            ['name' => 'editor', 'created_at' => $this->dateNow(), 'updated_at' => $this->dateNow()],
            ['name' => 'dictionary-moderator', 'created_at' => $this->dateNow(), 'updated_at' => $this->dateNow()],
        ]);
    }
}
