<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SistemaUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sistema_usuario')->insert([
            'usuario' => 'nelson.tarche',
            'nombre' => 'Nelson Daniel',
            'apellido' => 'Tarche',
            'email' => 'nelson.tarche@fce.uba.ar',
            'clave' => Hash::make('laravel'),
            'activo' => 1,
            'root' => 1,
            'fk_grupo_id' => 1
        ]);

        DB::table('sistema_usuario')->insert([
            'usuario' => 'usuario2',
            'nombre' => 'Nombre2',
            'apellido' => 'Apellido2',
            'email' => 'usuario2@mail.com',
            'clave' => Hash::make('laravel'),
            'activo' => 1,
            'root' => 1,
            'fk_grupo_id' => 1
        ]);

        DB::table('sistema_usuario')->insert([
            'usuario' => 'usuario3',
            'nombre' => 'Nombre3',
            'apellido' => 'Apellido3',
            'email' => 'usuario3@mail.com',
            'clave' => Hash::make('laravel'),
            'activo' => 1,
            'root' => 1,
            'fk_grupo_id' => 1
        ]);

        DB::table('sistema_usuario')->insert([
            'usuario' => 'usuario4',
            'nombre' => 'Nombre4',
            'apellido' => 'Apellido4',
            'email' => 'usuario4@mail.com',
            'clave' => Hash::make('laravel'),
            'activo' => 1,
            'root' => 1,
            'fk_grupo_id' => 1
        ]);

        DB::table('sistema_usuario')->insert([
            'usuario' => 'usuario5',
            'nombre' => 'Nombre5',
            'apellido' => 'Apellido5',
            'email' => 'usuario5@mail.com',
            'clave' => Hash::make('laravel'),
            'activo' => 1,
            'root' => 1,
            'fk_grupo_id' => 1
        ]);
    }
}