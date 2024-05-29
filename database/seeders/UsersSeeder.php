<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;

/**
 * Создание тестовых пользователей
 */
class UsersSeeder extends AbstractSeeder
{    

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codeRegion = $this->getRegionCode();

        $user = new User([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'org_code' => "{$codeRegion}00",
        ]);
        $user->save();
        $user->updateRoles(['admin']);

        $user2 = new User([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
            'org_code' => "{$codeRegion}00",
        ]);
        $user2->save();
    }
}
