<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
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
        $user = User::create([
            'id' => 20,
            'nama' => 'Sawal',
            'email' => 'ifsawal@gmail.com',
            'password' => bcrypt('1'),
            'j_permisi' => 10,
            'email_verified_at' => Carbon::now(),
            'pdam_id' => 1,
        ]);
        $user1 = User::create([
            'id' => 1,
            'nama' => 'Ramhlah',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user2 = User::create([
            'id' => 2,
            'nama' => 'Candrawati',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user3 = User::create([
            'id' => 3,
            'nama' => 'Sumardiani',
            'email' => 'user3@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user4 = User::create([
            'id' => 4,
            'nama' => 'Susianti',
            'email' => 'user4@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user5 = User::create([
            'id' => 5,
            'nama' => 'Maulina Sari',
            'email' => 'user5@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user6 = User::create([
            'id' => 6,
            'nama' => 'Lisa Ivania',
            'email' => 'user6@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user7 = User::create([
            'id' => 7,
            'nama' => 'Mahdi Putra',
            'email' => 'user7@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user8 = User::create([
            'id' => 8,
            'nama' => 'Hamisah',
            'email' => 'user8@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user9 = User::create([
            'id' => 9,
            'nama' => 'Elti Jusmari',
            'email' => 'user9@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user10 = User::create([
            'id' => 10,
            'nama' => 'Yunika Putri Bintang',
            'email' => 'user10@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user11 = User::create([
            'id' => 11,
            'nama' => 'Sukria',
            'email' => 'user11@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user12 = User::create([
            'id' => 12,
            'nama' => 'Evia Fitriani',
            'email' => 'user12@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user13 = User::create([
            'id' => 13,
            'nama' => 'Putra Abadi',
            'email' => 'user13@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);
        $user14 = User::create([
            'id' => 14,
            'nama' => 'Putra Abadi',
            'email' => 'user14@gmail.com',
            'password' => bcrypt('1234'),
            'j_permisi' => 10,
            'pdam_id' => 1,
        ]);



        $role = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

        $permission = Permission::create(['name' => 'lihat users', 'guard_name' => 'web']);
        $permission_tambah = Permission::create(['name' => 'tambah users', 'guard_name' => 'web']);
        $permission_edit = Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
        $permission_hapus = Permission::create(['name' => 'hapus users', 'guard_name' => 'web']);

        $r = Permission::create(['name' => 'lihat role', 'guard_name' => 'web']);
        $r_tambah = Permission::create(['name' => 'tambah role', 'guard_name' => 'web']);
        $r_edit = Permission::create(['name' => 'edit role', 'guard_name' => 'web']);
        $r_hapus = Permission::create(['name' => 'hapus role', 'guard_name' => 'web']);

        $p = Permission::create(['name' => 'lihat permisi', 'guard_name' => 'web']);
        $p_tambah = Permission::create(['name' => 'tambah permisi', 'guard_name' => 'web']);
        $p_edit = Permission::create(['name' => 'edit permisi', 'guard_name' => 'web']);
        $p_hapus = Permission::create(['name' => 'hapus permisi', 'guard_name' => 'web']);

        //role-permisi
        $rp = Permission::create(['name' => 'lihat rp', 'guard_name' => 'web']);
        $rp_tambah = Permission::create(['name' => 'tambah rp', 'guard_name' => 'web']);
        $rp_edit = Permission::create(['name' => 'edit rp', 'guard_name' => 'web']);
        $rp_hapus = Permission::create(['name' => 'hapus rp', 'guard_name' => 'web']);

        //user
        $user = Permission::create(['name' => 'lihat user', 'guard_name' => 'web']);
        $user_tambah = Permission::create(['name' => 'tambah user', 'guard_name' => 'web']);
        $user_edit = Permission::create(['name' => 'edit user', 'guard_name' => 'web']);
        $user_hapus = Permission::create(['name' => 'hapus user', 'guard_name' => 'web']);

        //user
        $pdam = Permission::create(['name' => 'lihat pdam', 'guard_name' => 'web']);
        $pdam_tambah = Permission::create(['name' => 'tambah pdam', 'guard_name' => 'web']);
        $pdam_edit = Permission::create(['name' => 'edit pdam', 'guard_name' => 'web']);
        $pdam_hapus = Permission::create(['name' => 'hapus pdam', 'guard_name' => 'web']);

        //pelanggan
        $pelanggan = Permission::create(['name' => 'lihat pelanggan', 'guard_name' => 'web']);
        $pelanggan_tambah = Permission::create(['name' => 'tambah pelanggan', 'guard_name' => 'web']);
        $pelanggan_edit = Permission::create(['name' => 'edit pelanggan', 'guard_name' => 'web']);
        $pelanggan_hapus = Permission::create(['name' => 'hapus pelanggan', 'guard_name' => 'web']);
        $pelanggan_setujui = Permission::create(['name' => 'setujui pelanggan', 'guard_name' => 'web']);

        $role->givePermissionTo(Permission::all());



        $role_admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $role_admin->givePermissionTo($permission);
        $permission->assignRole($role_admin);
        $permission_tambah->assignRole($role_admin);
        $permission_edit->assignRole($role_admin);
        $permission_hapus->assignRole($role_admin);

        $user1->assignRole($role);
        $user2->assignRole($role_admin);
    }
}
