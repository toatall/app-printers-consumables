<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'org_code' => '0000',
        ]);
        $user->save();
        $user->updateRoles(['admin']);

        $user2 = new User([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
            'org_code' => '0001',
        ]);
        $user2->save();
    }
}
