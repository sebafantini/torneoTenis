<?php

namespace Database\Seeders;

use App\Models\jugador;
use Illuminate\Database\Seeder;

class JugadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        jugador::factory(32)->masculino()->create();     
        jugador::factory(32)->femenino()->create();        
    }
}
