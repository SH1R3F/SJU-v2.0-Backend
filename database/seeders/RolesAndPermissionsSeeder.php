<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Admins seeder
        $superadmin = Admin::create([
          'username' => 'مدير النظام',
          'email' => 'admin@sju.org',
          'mobile' => '01000000000',
          'branch_id' => null,
          'password' => bcrypt('password'),
        ]);
        $branch_manager = Admin::create([
          'username' => 'مدير فرع',
          'email' => 'manager@branch.org',
          'mobile' => '02000000000',
          'branch_id' => 1,
          'password' => bcrypt('password'),
        ]);
        $news_editor = Admin::create([
          'username' => 'محرر أخبار',
          'email' => 'editor@sju.org',
          'mobile' => '03000000000',
          'branch_id' => null,
          'password' => bcrypt('password'),
        ]);


        // Roles seeder
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'مدير الموقع', // optional
            'description' => 'مديرو الموقع', // optional
        ]);
        
        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'مدير فرع', // optional
            'description' => 'مدراء الفروع', // optional
        ]);
        $editor = Role::create([
            'name' => 'editor',
            'display_name' => 'محرر أخبار', // optional
            'description' => 'محررو الأخبار', // optional
        ]);

        // Permissions seeder
        $readUser = Permission::create([
            'name' => 'read-user',
            'display_name' => 'Read users', // optional
            'description' => 'Read users data', // optional
        ]);
        $updateUser = Permission::create([
            'name' => 'update-post',
            'display_name' => 'Update users', // optional
            'description' => 'Update users data', // optional
        ]);
        $deleteUser = Permission::create([
            'name' => 'delete-user',
            'display_name' => 'Delete users', // optional
            'description' => 'Delete users data', // optional
        ]);

        // Attach permissions to roles
        $admin->attachPermissions([$readUser, $updateUser, $deleteUser]);
        $manager->attachPermissions([$readUser, $updateUser]);
        $editor->attachPermissions([$readUser]);

        // Attach roles to admins
        $superadmin->attachRole($admin);
        $branch_manager->attachRole($manager);
        $news_editor->attachRole($editor);


    }
}
