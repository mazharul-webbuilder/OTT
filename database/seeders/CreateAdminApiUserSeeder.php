<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminApiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin Seeder
        $user = Admin::create([
            'name' => 'Super Admin Api',
            'email' => 'admin_api@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Super Admin Api', 'guard_name' => 'admin_api']);
        $permissions = Permission::where('guard_name', 'admin_api')->pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
