<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name'=> 'admin']);
        $managementRole = Role::create(['name'=> 'management']);
        $staffRole = Role::create(['name'=> 'staff']);
        $agenRole = Role::create(['name'=> 'agen']);

        $userOwner =  User::create([
            'name'  => 'Fulan',
            'avatar'  => 'images/default-avatar.png',
            'email'  => 'admin@umrohsafarindo.com',
            'password'  => bcrypt('123123123'),
        ]);

        $userOwner->assignRole($adminRole);
    }
}
