<?php


namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateContentManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = Admin::create([
        //     'name' => 'content Manager',
        //     'email' => 'admin1@gmail.com',
        //     'password' => bcrypt('123456')
        // ]);

        // $role = Role::create(['name' => 'Content Manager', 'guard_name' => 'admin']);

        // $permissions = Permission::pluck('id', 'id')->all();

        // $role->syncPermissions($permissions);

        // $user->assignRole([$role->id]);
    }
}
