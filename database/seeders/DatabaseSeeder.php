<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        $role = Role::create(['name' => 'Administrador']);
        $user->assignRole($role);


        
           // Permisos para clientes
           DB::table('permissions')->insert(['name' => 'ver client','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear cliente','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'edit client','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete client','guard_name' => 'web']);
   
           // Permisos para permisos
           DB::table('permissions')->insert(['name' => 'ver permiso','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear permiso','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'update permiso','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete permiso','guard_name' => 'web']);
   
           // Permisos para representantes
           DB::table('permissions')->insert(['name' => 'ver represent','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'create represent','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'edit represent','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete represent','guard_name' => 'web']);
   
           // Permisos para servicios
           DB::table('permissions')->insert(['name' => 'ver servise','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear service','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'update service','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete service','guard_name' => 'web']);

           
           // Permisos para los role
           DB::table('permissions')->insert(['name' => 'ver role','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear role','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'update role','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete role','guard_name' => 'web']);

           
           // Permisos para users
           DB::table('permissions')->insert(['name' => 'ver usuario','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear usuario','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'edit usuario','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete usuario','guard_name' => 'web']);

           
           // Permisos para vehicle
           DB::table('permissions')->insert(['name' => 'ver vehicle','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'crear vehicle','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'update vehicle','guard_name' => 'web']);
           DB::table('permissions')->insert(['name' => 'delete vehicle','guard_name' => 'web']);


           //table roles
           DB::table('roles')->insert(['name' => 'Conductores','guard_name' => 'web']);
           DB::table('roles')->insert(['name' => 'Representante','guard_name' => 'web']);
        
    }
}
