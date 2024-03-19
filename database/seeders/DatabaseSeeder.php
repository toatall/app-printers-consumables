<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::factory()->createMany([
            ['name' => 'admin'],
            ['name' => 'editor-stock'],
            ['name' => 'editor-local'],
            ['name' => 'reader'],
        ]);
        // Roles::create(['name' => 'admin']);
        // Roles::create(['name' => 'editor-stock']);
        // Roles::create(['name' => 'editor-local']);

        User::factory()->create([
            'name' => 'oleg',
            'email' => 'oleg@oleg.ru',
            'password' => Hash::make('secret'),
        ]);

        // $account = Account::create(['name' => 'Acme Corporation']);

        // User::factory()->create([
        //     'account_id' => $account->id,
        //     'first_name' => 'John',
        //     'last_name' => 'Doe',
        //     'email' => 'johndoe@example.com',
        //     'password' => 'secret',
        //     'owner' => true,
        // ]);

        // User::factory(5)->create(['account_id' => $account->id]);

        // $organizations = Organization::factory(100)
        //     ->create(['account_id' => $account->id]);

        // Contact::factory(100)
        //     ->create(['account_id' => $account->id])
        //     ->each(function ($contact) use ($organizations) {
        //         $contact->update(['organization_id' => $organizations->random()->id]);
        //     });
    }
}
