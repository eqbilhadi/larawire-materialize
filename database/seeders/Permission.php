<?php

namespace Database\Seeders;

use App\Models\SysPermission as ModelsPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsPermission::firstOrCreate(['name' => 'create menu', 'group' => 'Navigation']);
        ModelsPermission::firstOrCreate(['name' => 'edit menu', 'group' => 'Navigation']);
        ModelsPermission::firstOrCreate(['name' => 'delete menu', 'group' => 'Navigation']);
        ModelsPermission::firstOrCreate(['name' => 'sort menu', 'group' => 'Navigation']);

        ModelsPermission::firstOrCreate(['name' => 'create permission', 'group' => 'Permission']);
        ModelsPermission::firstOrCreate(['name' => 'edit permission', 'group' => 'Permission']);
        ModelsPermission::firstOrCreate(['name' => 'delete permission', 'group' => 'Permission']);

        ModelsPermission::firstOrCreate(['name' => 'create role', 'group' => 'Role']);
        ModelsPermission::firstOrCreate(['name' => 'edit role', 'group' => 'Role']);
        ModelsPermission::firstOrCreate(['name' => 'delete role', 'group' => 'Role']);

        ModelsPermission::firstOrCreate(['name' => 'create user', 'group' => 'User']);
        ModelsPermission::firstOrCreate(['name' => 'edit user', 'group' => 'User']);
        ModelsPermission::firstOrCreate(['name' => 'delete user', 'group' => 'User']);

        $developer = \App\Models\SysRole::where('name', 'developer')->first();

        $developer->syncPermissions([
            'create menu',
            'edit menu',
            'delete menu',
            'sort menu',
            'create permission',
            'edit permission',
            'delete permission',
            'create role',
            'edit role',
            'delete role',
            'create user',
            'edit user',
            'delete user',
        ]);
    }
}
