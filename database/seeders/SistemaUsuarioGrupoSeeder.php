<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaUsuarioGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++)
            DB::table('sistema_usuario_grupo')->insert([
                'fk_usuario_id' => $i,
                'fk_grupo_id' => 1,
                'predeterminado' => 1
            ]);
    }
}