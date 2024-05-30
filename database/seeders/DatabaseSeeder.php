<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'list users']); //index
        Permission::create(['name' => 'show users']); //show individual user
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // create roles and assign existing permissions
        $member = Role::create(['name' => 'member']);
        $member->givePermissionTo('edit users');
        $member->givePermissionTo('delete users');

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('show users');
        $admin->givePermissionTo('edit users');
        $admin->givePermissionTo('list users');

        $superadmin = Role::create(['name' => 'superadmin']);

        // create superadmin
        $user = \App\Models\User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'elsakcf@gmail.com',
        ]);
        $user->assignRole($superadmin);

        // create admin
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($admin);

        // create member
        $user = \App\Models\User::factory()->create([
            'name' => 'Member User',
            'email' => 'test@example.com',
        ]);
        $user->assignRole($member);

        $this->call(CountrySeeder::class);
        $this->call(LanguageSeeder::class);
    }
}
