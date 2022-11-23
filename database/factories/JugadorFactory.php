<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JugadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=>$this->faker->unique()->name('male'),
            'genero'=>'M', 
            'habilidad'=>$this->faker->numberBetween(1,100),
            'fuerza'=>$this->faker->numberBetween(1,30),
            'velocidad'=>$this->faker->numberBetween(1,30),
            'tiemporeaccion'=>$this->faker->numberBetween(1,30),
            
        ];
    }

    public function femenino()
    {
    return $this->state(function (array $attributes) {
        return [
            'nombre'=>$this->faker->unique()->name('female'),
            'genero'=>'F', 
        ];
    });
    }

    public function masculino()
    {
    return $this->state(function (array $attributes) {
        return [
            'nombre'=>$this->faker->unique()->name('male'),
            'genero'=>'M', 
        ];
    });
    }
}
