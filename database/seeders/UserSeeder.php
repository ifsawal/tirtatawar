<?php

namespace Database\Seeders;

use App\Models\User;
use PhpParser\Node\Expr\New_;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user1=User::create([
            'nama' => 'Sawal',
            'email' => 'ifsawal@gmail.com',
            'password' => bcrypt('1'),
            'j_permisi' => 10,
            ]);
        $user2=User::create([
            'nama' => 'aa',
            'email' => 'aa@gmail.com',
            'password' => bcrypt('1'),
            'j_permisi' => 10,
            ]);
        $user3=User::create([
            'nama' => 'bb',
            'email' => 'bb@gmail.com',
            'password' => bcrypt('1'),
            'j_permisi' => 10,
            ]);


            $role = Role::create(['name' => 'super_admin', 'guard_name'=>'web']);

            $permission = Permission::create(['name' => 'lihat users', 'guard_name'=>'web']);  
            $permission_tambah = Permission::create(['name' => 'tambah users', 'guard_name'=>'web']);  
            $permission_edit = Permission::create(['name' => 'edit users', 'guard_name'=>'web']);  
            $permission_hapus = Permission::create(['name' => 'hapus users', 'guard_name'=>'web']);
            
            $r = Permission::create(['name' => 'lihat role', 'guard_name'=>'web']);  
            $r_tambah = Permission::create(['name' => 'tambah role', 'guard_name'=>'web']);  
            $r_edit = Permission::create(['name' => 'edit role', 'guard_name'=>'web']);  
            $r_hapus = Permission::create(['name' => 'hapus role', 'guard_name'=>'web']);

            $p = Permission::create(['name' => 'lihat permisi', 'guard_name'=>'web']);  
            $p_tambah = Permission::create(['name' => 'tambah permisi', 'guard_name'=>'web']);  
            $p_edit = Permission::create(['name' => 'edit permisi', 'guard_name'=>'web']);  
            $p_hapus = Permission::create(['name' => 'hapus permisi', 'guard_name'=>'web']);

            $rp = Permission::create(['name' => 'lihat rp', 'guard_name'=>'web']);  
            $rp_tambah = Permission::create(['name' => 'tambah rp', 'guard_name'=>'web']);  
            $rp_edit = Permission::create(['name' => 'edit rp', 'guard_name'=>'web']);  
            $rp_hapus = Permission::create(['name' => 'hapus rp', 'guard_name'=>'web']);
            
            $role->givePermissionTo(Permission::all());
        
            

            $role_admin = Role::create(['name' => 'admin', 'guard_name'=>'web']);

            $role_admin->givePermissionTo($permission);     
            $permission->assignRole($role_admin);               
            $permission_tambah->assignRole($role_admin);               
            $permission_edit->assignRole($role_admin);               
            $permission_hapus->assignRole($role_admin);          
             
            $user1->assignRole($role);
            $user2->assignRole($role_admin);
            
            


    }
}
