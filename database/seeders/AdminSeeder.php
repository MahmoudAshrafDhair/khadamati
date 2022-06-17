<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin =Admin::query()->create([
           'name' => 'Mahmoud Dhair',
           'email' => 'admin@gmail.com',
           'password' => Hash::make(123456)
        ]);
        $role = Role::create(['name' => 'Admin','guard_name' => 'admin']);
        $permissions = Permission::query()->pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole($role->id);
    }
}
