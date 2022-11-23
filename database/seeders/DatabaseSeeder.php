<?php

namespace Database\Seeders;

use App\Models\jugador;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(JugadorSeeder::class);
    }
}
