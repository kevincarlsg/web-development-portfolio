<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Limpia la tabla de usuarios y roles
        User::truncate();
        Role::truncate();

        // Crea roles
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        // ========  ADMINISTRADORES  ========

        // 1. Kevin Sánchez
        $user1 = User::factory()->create([
            'name' => 'Kevin Sánchez',
            'email' => 'user@user.com',   // tal como lo querías
        ]);
        $user1->assignRole($adminRole);

        // 2. David González
        $user2 = User::factory()->create([
            'name' => 'David González',
            'email' => 'admindavid@admin.com',
        ]);
        $user2->assignRole($adminRole);

        // 3. Ámbar Carbajal
        $user3 = User::factory()->create([
            'name' => 'Ámbar Carbajal',
            'email' => 'adminambar@admin.com',
        ]);
        $user3->assignRole($adminRole);

        // ========  CLIENTES  (17 usuarios restantes)  ========

        User::factory()
            ->count(17)
            ->create()
            ->each(function ($user) use ($clientRole) {
                $user->assignRole($clientRole);
            });
    }
}
